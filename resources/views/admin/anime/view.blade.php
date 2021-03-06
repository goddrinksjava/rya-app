<x-layout title="{{ $anime->title }}">
    <div class="bg-white dark:bg-gray-900 flex justify-center items-center min-h-screen">
        <div class="flex px-8 lg:px-0 w-full lg:w-2/3">
            <div class="flex-1">

                <div class="flex flex-row justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold text-gray-700 dark:text-white">
                            {{ $anime->title }}
                        </h2>
                    </div>
                    <div class="w-[200px] h-[300px]">
                        <img class="w-full h-full object-cover" src="/storage/posters/{{ $anime->id }}" alt="Poster">
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-xl font-bold text-gray-700 dark:text-white">Synopsis</p>
                    <p class="text-gray-700 dark:text-white">{!! nl2br($anime->synopsis) !!}</p>
                </div>

                <div class="flex justify-between flex-wrap mt-6">
                    <div>
                        <h1 class="text-xl text-gray-600 dark:text-gray-200">Genres</h1>
                        @foreach ($anime->genres as $genre)
                            <span class="relative inline-block px-3 py-1 font-semibold text-gray-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-gray-200 opacity-50 rounded-full"></span>
                                <span class="relative">{{ $genre->name }}</span>
                            </span>
                        @endforeach
                    </div>


                    <div>
                        <video width="512" height="288" controls>
                            <source src="/storage/trailers/{{ $anime->id }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ url('/admin/anime/' . $anime->id . '/edit') }}"
                        class="block text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Edit
                    </a>
                </div>

                <form action="/admin/anime/{{ $anime->id }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <div class="mt-1 text-center">
                        <button type="submit"
                            class="w-full text-gray-300 bg-rose-900 hover:bg-rose-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Delete
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</x-layout>
