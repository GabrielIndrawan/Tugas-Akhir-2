<x-layout title="ShareEasy" activeButton="homePage">
    <form method="POST" action="{{ route('users.update', $user["id"]) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="{{ $user["id"] }}" class="mt-1 pl-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex flex-row justify-center">
            <button type="submit" class="px-6 py-3 rounded-lg bg-blue-500 text-white" name="update-button">
                Update User
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