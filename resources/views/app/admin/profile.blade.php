<x-layout title="ShareEasy" activeButton="profile">

    <h2 class="text-xl font-bold">
        Profile Admin
    </h2>
    <div class="flex flex-col items-center">
        <img class="mt-4 w-32 h-32 rounded-full" src="{{ asset('images/admin-profile.png') }}" alt="Admin Profile Picture">
        <h3 class="text-lg font-bold">
            Admin
        </h3>
        <p class="mt-2 text-gray-600 mb-2">
            Di sini Anda dapat mengelola profile pic dari Admin.
        </p>
        <button class="px-2 py-2 bg-blue-500 text-white rounded-lg">
            Change Profile Picture
        </button>
    </div>
</x-layout>