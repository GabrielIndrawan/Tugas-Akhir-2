<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Subscribtion;
use App\Services\Nextcloud\NextcloudClient;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    //
    public function __construct(
        public NextcloudClient $nextcloudClient
    ){}
    public function handle(Request $request)
    {
        logger("Stripe received");
        $payload = $request->getContent();
        logger("Stripe received 2.0");
        $sigHeader = $request->header('Stripe-Signature');
        logger("Stripe received 3.0");
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                // Handle subscription update logic here
                $subscription = $event->data->object;
                $user = \App\Models\UserProfile::where("username", $subscription->metadata->user_id)->first();
                $user->update([
                    'premium' => true,
                    'current_quota' => $user->current_quota + 5,
                ]);

                $user = \App\Models\UserProfile::where("username", $subscription->metadata->user_id)->first();
                $user->update([
                    'premium' => true,
                    'current_quota' => $user->current_quota + 5,
                ]);

                $result = $this->nextcloudClient->addUserQuota($user->username, 'quota', $user->current_quota, 5);

                return response()->json(['status' => 'success'], 200);

                break;
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                // Handle subscription deletion logic here

                return response()->json(['status' => 'error'], 400);
                break;
            case 'customer,session.expired':
                $subscription = $event->data->object;
                // Handle subscription expiration logic here

                Subscribtion::where("stripe_session_id", $subscription->id)->delete();

                return response()->json(['status' => 'error'], 400);
                break;
            default:
                // Unexpected event type
                return response()->json(['error' => 'Unexpected event type'], 400);
        }
    }
}
