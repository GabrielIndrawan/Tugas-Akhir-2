<x-layout title="ShareEasy" activeButton="homePage">
    <h2 class="text-xl font-bold">
        Selamat datang {{ $name }}!
    </h2>
    <main>
        <section>
        <h3 class="text-lg font-bold mb-2">Storage</h3>
        <p class="text-gray-600 mb-4">Kelola file Anda di sini.</p>
        <div class="w-full max-w-md">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Storage Used</span>
                <span class="text-sm text-gray-600">10 / 25 GB</span>
            </div>
            <div class="w-full bg-gray-300 rounded-full h-4 overflow-hidden">
                <div
                    class="bg-blue-500 h-4 rounded-full"
                    style="width: 40%"
                ></div>
            </div>
        </div>
        <div class="my-4"></div>
        <a href="{{ route('storage') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
            Go to Storage
        </a>
        <div class="my-4"></div>
        </section>
        <section>
        <h3 class="text-lg font-bold mb-2">Subscription</h3>
        <p class="text-gray-600 mb-4">Kelola langganan Anda di sini.</p>
        <button class="bg-blue-500 text-white rounded-lg h-[50px] w-40">
            +5 GB Storage
            <br>
            <span class="ml-2 text-md">Starter Plan</span>
        </button>
        <button class="bg-blue-500 text-white rounded-lg h-[50px] w-40">
            +10 GB Storage
            <br>
            <span class="ml-2 text-md">Premium Plan</span>
        </button>
        </section>
    </main>
</x-layout>