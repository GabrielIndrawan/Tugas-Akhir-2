<x-layout title="ShareEasy" activeButton="profile">

    <h2 class="text-xl font-bold">
        Profile Anda
    </h2>
    <div class="flex flex-col items-center">
        <img class="mt-4 w-32 h-32 rounded-full" src="{{ asset('images/admin-profile.png') }}" alt="Admin Profile Picture">
        <h3 class="text-lg font-bold">
            {{$user->id}}
        </h3>
        <p class="mt-2 text-gray-600 mb-2">
            Di sini Anda dapat mengelola profile pic anda.
        </p>
        <button class="px-2 py-2 bg-blue-500 text-white rounded-lg">
            Change Profile Picture
        </button>
        <div class="my-2"></div>
        <form class="flex flex-col items-center gap-2" method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
            @csrf
            @method('PUT')
            <input type="text" name="username" placeholder="Enter new username" class="px-2 py-1 border rounded-lg">
            <button class="px-2 py-2 bg-blue-500 text-white rounded-lg">
                Change Username
            </button>
        </form>
    </div>
</x-layout>