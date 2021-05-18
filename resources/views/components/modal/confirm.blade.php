@props(['id' => null, 'maxWidth' => null])

<x-senna.modal.basic :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="bg-white w-full">
        <div class="flex space-x-3 items-center  p-7">
            <div class="text-red-600 rounded-full bg-red-100 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-2xl leading-none font-semibold text-black mb-2">
                    {{ $title }}
                </h3>
                <div>
                    {{ $content }}
                </div>
            </div>
        </div>
        <div class="bg-gray-100 p-4 space-x-2 flex justify-end rounded-md">
            {{ $footer }}
        </div>
    </div>
</x-modal.basic>
