<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 w-[50vw] rounded-xl shadow-md text-center">
        @if ($order->status === 'paid')
            <div class="text-green-600 text-4xl mb-4">
                ✓
            </div>

            <h1 class="text-2xl font-bold mb-2">
                Payment Successful!
            </h1>
        @else
            <h1 class="text-2xl font-bold mb-2">
                Payment Not Completed
            </h1>
        @endif

        <p class="text-gray-600 mb-2">
            Product: {{ $order->product_name }}
        </p>

        <p class="text-gray-600 mb-2">
            Amount: 
            {{ number_format($order->amount) }} GB
        </p>

        <p class="text-gray-600 mb-6">
            Status: {{ ucfirst($order->status) }}
        </p>

        <a href="{{ route('dashboard') }}"
           class="bg-blue-600 text-white px-5 py-3 rounded-lg">
            Back to Dashboard
        </a>
    </div>

</body>
</html>