<x-layout accesskey="" title="ShareEasy" activeButton="storage">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
        Storage Manager
    </h2>
    <article>
        <section>
            <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                @Method('POST')
                @csrf
                <input type="file" name="file" id="fileInput" class="mb-4 required">
                <br>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg" id="uploadButton">
                    Upload File
                </button>
            </form>
            <div class="flex flex-row gap-3 items-center">
                <form method="POST" action="{{ route('subscription.checkout') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="storage_size" value="5">
                    <input type="hidden" name="product_name" value="5 GB Storage">
                    <input type="hidden" name="price_id" value="price_1UNXyTBcPV60BVq3aiXWyp3w">
                    <button type="submit" class="my-3 px-4 py-2 bg-blue-500 text-white rounded-lg">
                        Buy +5 GB Storage
                    </button>
                </form>
                <form method="POST" action="">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="storage_size" value="10">
                    <input type="hidden" name="product_name" value="10 GB Storage">
                    <input type="hidden" name="price_id" value="price_1UNXyTBcPV60BVq3aiXWyp3w">
                    <button type="submit" class="my-1 px-4 py-2 bg-blue-500 text-white rounded-lg">
                        Buy +10 GB Storage
                    </button>
                </form>
            </div>
            <div class="my-2"></div>
            @if (session('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-800 rounded justify-center">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-800 rounded justify-center">
                    {{ session('error') }}
                </div>
            @endif
            
            <table class="">
                <tr>
                    <th>File Name</th>
                    <th>File Size</th>
                    <th>Uploaded At</th>
                    <th>File Type</th>
                    <th>Options</th>
                </tr>
                @foreach ($files as $file)
                    <tr class="hover:bg-slate-300 cursor-pointer" onclick="window.location='{{ route('files.show', ['file' => $file['name']]) }}'">
                        <td class="py-2 px-2 border-b">{{ $file['name'] }}</td>
                        <td class="py-2 px-2 border-b">{{ convertRawValueFromByte($file['size']) }}</td>
                        <td class="py-2 px-2 border-b">{{ $file['last_modified'] }}</td>
                        <td class="py-2 px-2 border-b">{{ $file["type"] }}</td>
                        <td class="py-2 px-2 border-b text-center" onclick="event.stopPropagation()">
                            <form method="POST" action="{{ route('files.destroy', ['file' => $file["name"]]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg">
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
        </section>
    </article>
</x-layout>