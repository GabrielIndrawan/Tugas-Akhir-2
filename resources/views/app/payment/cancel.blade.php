<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Cancelled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-md text-center">
        <div class="text-red-600 text-4xl mb-4">
            ×
        </div>

        <h1 class="text-2xl font-bold text-red-600 mb-2">
            Payment Cancelled
        </h1>

        <p class="text-gray-600 mb-6">
            Your payment was not completed.
        </p>

        <a href="{{ route('subscription.checkout') }}"
           class="bg-indigo-600 text-white px-5 py-3 rounded-lg">
            Try Again
        </a>
        &nbsp;
        <a href="{{ route('storage') }}"
          class="bg-indigo-600 text-white px-5 py-3 rounded-lg">
            Back
        </a>
    </div>

</body>
</html>