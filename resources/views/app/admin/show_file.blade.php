<x-layout accesskey="" title="ShareEasy" activeButton="storage">
    <h2 class="text-xl font-bold">
        File Details
    </h2>
    <section>
        <div class="flex flex-row gap-5">
            <a href="{{ route('storage') }}" class="mt-4 text-blue-500 hover:text-blue-700">
                Back to Storage Page
            </a>
            <a href="{{ route('files.download', ['filePath' => $fileName]) }}" class="mt-4 text-blue-500 hover:text-blue-700">
                Download File
            </a>
        </div>
    </section>
    <section>
    <div class="flex flex-col items-center">
        <h2 class="text-xl font-bold mb-4">
            {{ $fileName }}
        </h2>
    @if (str_starts_with($mimeType, 'image/'))

        {{-- Image --}}
        <img
            src="data:{{ $mimeType }};base64,{{ base64_encode($fileContent) }}"
            class="max-w-full max-h-[600px] rounded-lg"
            alt="{{ $fileName }}"
        >

    @elseif (str_starts_with($mimeType, 'text/'))

        {{-- Text --}}
        <pre class="p-4 bg-gray-100 rounded-lg whitespace-pre-wrap">{{ $fileContent }}</pre>

    @elseif ($mimeType === 'application/pdf')

        {{-- PDF --}}
        <iframe
            src="data:application/pdf;base64,{{ base64_encode($fileContent) }}"
            class="w-full h-[700px]"
        ></iframe>

    @else

        {{-- Unsupported preview --}}
        <div class="p-4 bg-gray-100 rounded-lg">
            <p>Preview is not available for this file type.</p>
        </div>

    @endif
    </div>
    </section>
    
</x-layout>