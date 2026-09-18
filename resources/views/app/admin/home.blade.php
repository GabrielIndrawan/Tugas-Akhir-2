<x-layout title="ShareEasy" activeButton="homePage">
    <h2 class="text-xl font-bold">
        Selamat datang di Halaman Admin!
    </h2>
    <p class="mt-2 text-gray-600 mb-2">
        Di sini Anda dapat mengelola pengguna, melihat statistik, dan melakukan tugas administratif lainnya.
    </p>
    <div class="flex gap-4">
        <a class="px-6 py-3 rounded-lg bg-blue-500 text-white" href="{{ route('users.create') }}">
            Add User
        </a>
        <a class="px-6 py-3 rounded-lg bg-blue-500 text-white" href="">
            Settings
        </a>
    </div>
    <h2 class="text-lg font-bold mt-4">Daftar Pengguna</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Role</th>
                <th class="py-2 px-4 border-b">Used Quota</th>
                <th class="py-2 px-4 border-b">Free Quota</th>
                <th class="py-2 px-4 border-b">Total Quota</th>
                <th class="py-2 px-4 border-b">Premium</th>
                <th class="py-2 px-4 border-b">Edit User</th>
                <th class="py-2 px-4 border-b">Delete User</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="py-2 px-4 border-b">John Doe</td>
                <td class="py-2 px-4 border-b">Admin</td>
                <td class="py-2 px-4 border-b">Test</td>
                <td class="py-2 px-4 border-b">Yes</td>
            </tr>
            @foreach ($users as $user)
            <tr>
                <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                <td class="py-2 px-4 border-b">Admin</td>
                <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->storageUsed ) }}</td>
                <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->quotaLeft ) }}</td>
                <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->storageQuota ) }}</td>
                <td class="py-2 px-4 border-b">{{ $user->premium ? 'Yes' : 'No' }}</td>
                <td>
                    <button class="px-4 py-2 rounded-lg bg-yellow-500 text-white" onclick="{{ route('users.edit', ['user' => $user->id]) }}">
                        Manage User
                    </button>
                </td>
                <td>
                    <button class="px-4 py-2 rounded-lg bg-red-500 text-white" onclick="{{ route('users.destroy', ['user' => $user->id]) }}">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>