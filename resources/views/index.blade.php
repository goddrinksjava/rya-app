@push('styles')
@endpush

<x-layout title="Rate Your Anime">
    @include('shared.navbar')

    <div class="flex flex-wrap justify-around">
        @for ($i = 0; $i < 28; $i++)
            <div class="p-8 flex-shrink-0">
                <x-card name="Dragon Cube Z" score="8.9" />
            </div>
        @endfor
    </div>

    @include('shared.footer')
</x-layout>
