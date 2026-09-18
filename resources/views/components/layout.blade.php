@props([
    "activeButton" => "homePage"
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? "My App" }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="bg-gray-200 min-h-screen">
        <div class="flex flex-col ">
            <header class="flex items-center bg-blue-600 min-h-[13vh] pl-2 sticky top-0">
                <h1 class="text-3xl font-extrabold text-white">ShareEasy</h1>
            </header>
            <div class="flex md:flex-row flex-col">
                <nav class="flex-1 bg-white h-[4vh] md:h-[calc(100vh-13vh)] sticky left-0 top-[13vh] bottom-0">
                    <div class="flex overflow-x-auto flex-row md:flex-col items-center">
                        <x-nav.sidebar-button href="{{Auth::user()->id === 'admin' ? route('dashboard-admin') : route('dashboard')}}" :isActive="$activeButton === 'homePage'">Home Page</x-nav.sidebar-button>
                        <x-nav.sidebar-button href="{{ route('storage') }}" :isActive="$activeButton === 'storage'">Storage</x-nav.sidebar-button>
                        <x-nav.sidebar-button :isActive="$activeButton === 'settings'" class="{{ Auth::user()->id === 'admin' ? 'hidden' : '' }}">Settings</x-nav.sidebar-button>
                        <x-nav.sidebar-button :isActive="$activeButton === 'profile'">Profile/Info</x-nav.sidebar-button>
                        <a class="border md:w-full hover:bg-gray-200 md:hover:bg-gray-100 transtion duration-300 px-3 min-h-[4vh] md:py-5 font-bold text-red-500 text-sm" href="{{ route("logout") }}">Logout</a>
                    </div>
                </nav>
                <main class="flex-[4] bg-white mx-[2vh] md:mx-[10vh] p-5 my-4 rounded-xl">
                    {{ $slot }}
                </main>
            </div>
            <footer></footer>
        </div>
    </div>
</body>
</html>