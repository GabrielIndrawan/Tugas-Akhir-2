<x-layout title="ShareEasy" activeButton="subscription">
    <form method="POST" action="{{ route('subscription.add') }}">
        @csrf
        @method('POST')
        <div class="mb-4">
            <label for="subscription_name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="subscription_name" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 required">
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" name="amount" id="amount" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 required">
            <label for="price" class="block text-sm font-medium text-gray-700">Price (in Rp)</label>
            <input type="number" name="price" id="price" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 required">
        </div>
        <div class="flex flex-row justify-center">
            <button type="submit" class="px-6 py-3 rounded-lg bg-blue-500 text-white" name="update-button">
                Add Subscribtion
            </button>
        </div>
        @if (session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded justify-center">
                {{ session('error') }}
            </div>
        @endif
        @if (session("delete_success"))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
                {{ session('delete_success') }}
            </div>
        @endif
    </form>
    <section>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
            Subscribtion List
        </h2>
        <div class="overflow-y-auto">
        <table>
            <tr>
                <th class="py-2 px-2 border-b">Name</th>
                <th class="py-2 px-2 border-b">Amount</th>
                <th class="py-2 px-2 border-b">Price</th>
                <th class="py-2 px-2 border-b">Price Key</th>
                <th class="py-2 px-2 border-b">Edit Catalog</th>
                <th class="py-2 px-2 border-b">Delete Catalog</th>
            </tr>
            @foreach ($catalogs as $catalog)
                <tr class="hover:bg-slate-300 cursor-pointer">
                    <td class="py-2 px-2 border-b">{{ $catalog['name'] }}</td>
                    <td class="py-2 px-2 border-b">{{ $catalog['amount'] }}</td>
                    <td class="py-2 px-2 border-b">{{ $catalog['price'] }}</td>
                    <td class="py-2 px-2 border-b">{{ $catalog['price_key'] }}</td>
                    <td class="py-2 px-2 border-b">
                        <a class="px-4 py-2 rounded-lg bg-yellow-500 text-white" href="{{ route("subscription.edit") }}">
                            Edit
                        </a>
                    </td>
                    <td class="py-2 px-2 border-b">
                        <form method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <input class="hidden" name="productId" value="{{ $catalog['product_id'] }}">
                            <input class="hidden" name="priceId" value="{{ $catalog['price_key'] }}">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        </div>
    </section>
</x-layout>
<script>
    // Add event listener to the form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        // Disable the submit button to prevent multiple submissions
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitButton.textContent = 'Submitting...';
    });
</script>