<x-layout accesskey="" title="ShareEasy" activeButton="history">
    <h2 class="text-xl font-bold">
        Transaction History
    </h2>
    <section>
    <div class="overflow-y-auto">
            <table>
                <tr>
                    <th class="py-2 px-2 border-b">User</th>
                    <th class="py-2 px-2 border-b">Product Name</th>
                    <th class="py-2 px-2 border-b">Amount</th>
                    <th class="py-2 px-2 border-b">Status</th>
                </tr>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="py-2 px-2 border-b">{{ $transaction["user_id"] }}</td>
                        <td class="py-2 px-2 border-b">{{ $transaction["product_name"] }}</td>
                        <td class="py-2 px-2 border-b">{{ $transaction["amount"] }}</td>
                        <td class="py-2 px-2 border-b">{{ $transaction["status"] }}</td>
                    </tr>
                @endforeach
            </table>
            </div>
    </section>
    
</x-layout>