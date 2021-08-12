<x-senna.modal {{ $attributes }}>
    <section {{ $attributes->merge([ 'class' => 'p-6 mx-auto bg-white rounded-md shadow-lg dark:bg-gray-800' ]) }}>
        {{ $slot}}
    </section>
</x-senna.modal>