<x-layout title="ShareEasy" activeButton="homePage">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex flex-row justify-center">
            <button type="submit" class="px-6 py-3 rounded-lg bg-blue-500 text-white" name="add-button">
                Add User
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
    </form>
</x-layout>
<script>
    const form = document.querySelector('form');
    const addButton = form.querySelector('button[name="add-button"]');

    form.addEventListener('submit', function () {
        addButton.innerHTML = 'Adding...';
        addButton.disabled = true;
        addButton.classList.add('bg-gray-400', 'cursor-not-allowed');
    });
</script>