<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnimeResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.anime.table', ['anime' => Anime::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.anime.create', ['genres' => Genre::all()->map(function ($r) {
            return $r->name;
        })]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'genres.*' => 'exists:genres,name|distinct|min:1',
            'title' => 'required|unique:anime|min:1|max:40',
            'synopsis' => 'required|min:1|max:8000',
            'episodes' => 'required|integer|min:1',
            'imageUpload' => ['mimes:jpg,jpeg,png', 'max:16384'],
            'videoUpload' => ['nullable', 'mimes:mp4', 'max:122880'],
        ]);

        $anime = new Anime();
        $anime->title = $request->title;
        $anime->synopsis = $request->synopsis;
        $anime->episodes = $request->episodes;
        $anime->release_date = $request->release_date;
        $anime->created_at = Carbon::now();
        $anime->updated_at = Carbon::now();
        $anime->save();

        $genres = Genre::whereIn('name', $request->genres ?? [])->pluck('id')->toArray();
        $anime->genres()->sync($genres);

        if ($request->file('videoUpload')) {
            $request->file('videoUpload')->storeAs(
                'trailers',
                $anime->id,
                'public'
            );
        }

        $request->file('imageUpload')->storeAs(
            'posters',
            $anime->id,
            'public'
        );

        return view('admin.anime.table', ['anime' => Anime::with('genres')->get()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anime = Anime::findOrFail($id);
        return view('admin.anime.view', ['anime' => $anime]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anime = Anime::find($id);
        $genres = Genre::all()->map(function ($genre) {
            return (object)['name' => $genre->name, 'checked' => false];
        });

        foreach ($genres as $genre) {
            foreach ($anime->genres as $anime_genre) {
                $genre->checked = $genre->name == $anime_genre->name;
            }
        }

        return view('admin.anime.edit', ['anime' => $anime, 'genres' => $genres]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $anime = Anime::find($id);

        $request->validate([
            'genres.*' => 'exists:genres,name|distinct|min:1',
            'title' => ($anime->title == $request->title ? '' : 'required|unique:anime|min:1|max:40'),
            'synopsis' => 'required|min:1|max:8000',
            'episodes' => 'required|integer|min:1',
            'imageUpload' => ['nullable', 'mimes:jpg,jpeg,png', 'max:16384'],
            'videoUpload' => ['nullable', 'mimes:mp4', 'max:122880'],
        ]);

        $anime->title = $request->title;
        $anime->synopsis = $request->synopsis;
        $anime->episodes = $request->episodes;
        $anime->release_date = $request->release_date;
        $anime->updated_at = Carbon::now();
        $anime->save();

        $genres = Genre::whereIn('name', $request->genres ?? [])->pluck('id')->toArray();
        $anime->genres()->sync($genres);

        if ($request->file('videoUpload')) {
            $request->file('videoUpload')->storeAs(
                'trailers',
                $anime->id,
                'public'
            );
        }

        if ($request->file('imageUpload')) {
            $request->file('imageUpload')->storeAs(
                'posters',
                $anime->id,
                'public'
            );
        }


        return view('admin.anime.view', ['anime' => Anime::findOrFail($anime->id)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anime = Anime::find($id);
        $anime->delete();
        return redirect('/admin/anime');
    }
}
