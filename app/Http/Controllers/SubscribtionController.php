<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Subscribtion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class SubscribtionController extends Controller
{
    //
    private function stripe():StripeClient
    {
        return new StripeClient(config("services.stripe.secret"));
    }

    public function index(){

        $catalogs = Catalog::all();

        return view("app.admin.manage_subscription", ["catalogs" => $catalogs]);
    }

    public function checkout(Request $request){

        $subscribtion = Subscribtion::create([
            'user_id' => auth()->id(),
            'product_name' => $request->product_name,
            'currency' => "idr",
            'amount' => $request->storage_size,
        ]);

        $price = $request->price_id;
        $checkoutSession = $this->stripe()->checkout->sessions->create([
            'line_items' => [[
                'price' => $price, // Replace with your actual price ID
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('subscription.success'). '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscription.cancel'). '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'user_id' => (string) auth()->id(),
                "order_id" => (string) $subscribtion->id,
            ],
        ]);

        $subscribtion->update([
            'stripe_session_id' => $checkoutSession->id,
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            abort(400, 'Missing session ID.');
        }

        // Retrieve the actual session from Stripe
        $session = $this->stripe()->checkout->sessions->retrieve($sessionId);

        $orderId = $session->metadata->order_id ?? null;

        $subscribtion = Subscribtion::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('stripe_session_id', $session->id)
            ->firstOrFail();

        if ($session->payment_status === 'paid') {
            $subscribtion->update([
                'status' => 'paid',
            ]);
        }

        return view('app.payment.success', [
            'order' => $subscribtion,
        ]);
    }

    public function addSubscribtion(Request $request){
        try{
        $product = $this->stripe()->products->create([
            'name' => $request->input('name'),
        ]);

        $price = $this->stripe()->prices->create([
            'unit_amount' => $request->input('price') * 100, // Convert to cents
            'currency' => 'idr',
            'recurring' => ['interval' => 'month'],
            'product' => $product->id,
        ]);

        Catalog::firstOrCreate([
            'name' => $request->input('name'),
            
        ],[
            'product_id' => $product->id,
            'amount' => $request->input('amount'),
            'price' => $request->input('price'),
            'price_key' => $price->id,
        ]);

        return redirect()->back()->with('success', 'Subscription added successfully.');

        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }

    }

    public function destroy(Request $request){
        try{
        $this->stripe()->prices->update($request->input("priceId"), [
            'active' => false,
        ]);

        $this->stripe()->products->delete($request->input("productId"));

        Catalog::delete(
            $request->input('id')
        );

        }catch(\Exception $e){
            return redirect()->back()->with("delete_error",$e->getMessage());
        }

        return redirect()->back()->with("delete_success","Subscribtion deleted Succesfully");
    }

    public function edit(Request $request){
        return view("app.admin.edit_subscription");
    }

    public function update(Request $request){
        try{
            $this->stripe()->products->update(
                $request->input("productId"),
                [
                    'name' => $request->input('name'),
                ]
            );

            $this->stripe()->prices->update(
                $request->input('price_key'),
                [
                    'unit_amount' => $request->input('price') * 100,
                    'amount' => $request->input('amount')
                ]
            );

            return redirect()->back()->with("update_success","Sibscribtion updated successfully");
        }catch(\Exception $e){
            return redirect()->back()->with("update_error", $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            $subscribtion = Subscribtion::where('stripe_session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if ($subscribtion && $subscribtion->status === 'pending') {
                $subscribtion->update([
                    'status' => 'cancelled',
                ]);
            }
        }

        return view('app.payment.cancel');
    }
}
