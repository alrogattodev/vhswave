<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Media::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'availability' => 'required|string|in:available,rented',
            'rental_price' => 'required|numeric|min:0',
            'media_type' => 'required|string|max:50',
        ],
        [
            'rental_price.numeric' => 'O preço do aluguel deve ser um número válido (ex.: 4.99).',
            'availability' => 'O tipo de disponibilidade deve ser Disponível ou Indisponível.'
        ]);

        $media = Media::create($validated);
        return response()->json($media, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return response()->json($media);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
