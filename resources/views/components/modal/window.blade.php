<x-senna.modal {{ $attributes }}>
    <section {{ $attributes->merge([ 'class' => 'p-6 mx-auto bg-white rounded-md shadow-lg ' ]) }}>
        {{ $slot}}
    </section>
</x-senna.modal>