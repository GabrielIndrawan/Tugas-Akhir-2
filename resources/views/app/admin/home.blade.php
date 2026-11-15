<x-layout title="ShareEasy" activeButton="homePage">
    <h2 class="text-xl font-bold">
        Selamat datang di Halaman Admin!
    </h2>
    <p class="mt-2 text-gray-600 mb-2">
        Di sini Anda dapat mengelola pengguna, melihat statistik, dan melakukan tugas administratif lainnya.
    </p>
    <div class="flex gap-4">
        <a class="px-6 py-3 rounded-lg select-none bg-blue-500 text-white" href="{{ route('users.create') }}">
            Add User
        </a>
    </div>
    <h2 class="text-lg font-bold mt-4">Daftar Pengguna</h2>
    <div class="overflow-x-auto overflow-y-auto">
        <table class="min-w-full bg-white border rounded-lg mt-2">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nama</th>
                    <th class="py-2 px-4 border-b">Role</th>
                    <th class="py-2 px-4 border-b">Used Quota</th>
                    <th class="py-2 px-4 border-b">Free Quota</th>
                    <th class="py-2 px-4 border-b">Total Quota</th>
                    <th class="py-2 px-4 border-b">Premium</th>
                    <th class="py-2 px-4 border-b">Is Online</th>
                    <th class="py-2 px-4 border-b">Edit User</th>
                    <th class="py-2 px-4 border-b">Delete User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                    <td class="py-2 px-4 border-b">{{ ($user->id === "admin")? "Admin":"User"}}</td>
                    <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->storageUsed ) }}</td>
                    <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->quotaLeft ) }}</td>
                    <td class="py-2 px-4 border-b">{{ convertRawValueFromByte($user->storageQuota ) }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->premium ? 'Yes' : 'No' }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->isActive ? 'Online' : 'Offline'}}</td>
                    <td>
                        @if( $user->id === 'admin')
                            <span class="text-gray-500 select-none">Cannot edit admin credentials</span>
                        @else
                            <a class="px-4 py-2 rounded-lg select-none bg-yellow-500 text-white" href="{{ route('users.edit',["user" => $user->id]) }}">
                                Manage User
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($user->id === 'admin')
                            <span class="text-gray-500 select-none">Cannot delete admin</span>
                        @else
                            <form class="delete-form" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button ml-6 px-4 py-2 rounded-lg select-none bg-red-500 text-white">
                                Delete
                            </button>
                        </form>
                        @endif 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
            {{ session('success') }}
        </div>
    @endif
</x-layout>
<script>
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            const button = form.querySelector('.delete-button');
            const result = confirm('Are you sure you want to delete this user?');

            console.log(result);

            if(result) {
                button.innerText = 'Deleting...';
                button.disabled = true;
                button.classList.remove('bg-red-500');
                button.classList.add('bg-gray-400', 'cursor-not-allowed');
            }else{
                event.preventDefault();
            }
        });
    })
</script>