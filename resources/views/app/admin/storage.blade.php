<x-layout accesskey="" title="ShareEasy" activeButton="storage">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
        {{"Admin's Storage Page"}}
    </h2>
    <article>
        <section class="flex flex-row">
            <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                @Method('POST')
                @csrf
                <input type="file" name="file" id="fileInput" class="mb-4 required">
                <br>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg" id="uploadButton">
                    Upload File
                </button>
            </form> 
        </section>
        <div class="py-3"></div>
        @if (session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded justify-center">
                {{ session('error') }}
            </div>
        @endif
         @if (session('delete_success'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
                {{ session('delete_success') }}
            </div>
        @elseif (session('delete_error'))
            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded justify-center">
                {{ session('delete_error') }}
            </div>
        @endif
        <section>
            <div class="overflow-y-auto">
            <table>
                <tr>
                    <th>File Name</th>
                    <th>File Size</th>
                    <th>Uploaded At</th>
                    <th>File Type</th>
                    <th class="text-center">Options</th>
                </tr>
                @foreach ($files as $file)
                    <tr class="hover:bg-slate-300 cursor-pointer" onclick="window.location='{{ route('files.show', ['file' => $file['name']]) }}'">
                        <td class="py-2 px-2 border-b">{{ $file['name'] }}</td>
                        <td class="py-2 px-2 border-b">{{ convertRawValueFromByte($file['size']) }}</td>
                        <td class="py-2 px-2 border-b">{{ $file['last_modified'] }}</td>
                        <td class="py-2 px-2 border-b">{{ $file["type"] }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <form method="POST" action="{{ route('files.destroy', ['file' => $file["name"]]) }}"
                                onsubmit="
                                  event.stopPropagation(); 
                                  deleteButton.innerHTML = 'Deleting...'; 
                                  deleteButton.disabled = true;
                                  deleteButton.classList.add('bg-gray-400', 'cursor-not-allowed');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-500 hover:bg-red-700 text-white rounded-lg text-sm">
                                    @if ($file["type"] === "directory")
                                        Delete Directory
                                    @else
                                        Delete File
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            </div>
        </section>
    </article>
    <script>
        const uploadForm = document.querySelector('form[action="{{ route('files.store') }}"]');
        const uploadButton = document.getElementById('uploadButton');

        uploadForm.addEventListener('submit', function () {
            uploadButton.innerHTML = 'Uploading...';
            uploadButton.disabled = true;
            uploadButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        });

        for (const deleteForm of document.querySelectorAll('form[action^="{{ route('files.destroy', ['file' => $file['name']]) }}"]')) {
            const deleteButton = deleteForm.querySelector('button[type="submit"]');
            deleteForm.addEventListener('submit', function () {
                
            });
        }

    </script>
</x-layout>
