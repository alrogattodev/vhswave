<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = Media::all();
        return response()->json([
            'success' => true,
            'data' => $medias, 
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

        } catch (\Illuminate\Validation\ValidationException $e){ 
            return response()->json([
                'message' => 'Erro de validação.',
                'error' => $e->errors(),
            ], 422);            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o registro.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$id) {
            return response()->json([
                'message' => 'O ID do registro é obrigatório.'
            ], 400);
        }
        
        try {
            $media = Media::findOrFail($id);
            return response()->json($media);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'O registro não existe ou já foi excluído'
            ], 404);
        }         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$id) {
            return response()->json([
                'message' => 'O ID do registro é obrigatório.'
            ], 400);
        }
        try { 
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

            $media = Media::findOrFail($id);
            $media->update($validated);

            return response()->json($media, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Softly Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$id) {
            return response()->json([
                'message' => 'O ID do registro é obrigatório.'
            ], 400);
        }

        try {
            $media = Media::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'O registro não existe ou já foi excluído'
            ], 404);
        }

        try {
            $media->delete();
            return response()->json([
                'message' => 'Registro excluído com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir o registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore specified "soft deleted" resource 
     */
    public function restore(string $id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);
    
            if (!$media->trashed()) {
                return response()->json([
                    'message' => 'O registro não está excluído.'
                ], 400);
            }
    
            $media->restore();
    
            return response()->json([
                'message' => 'Registro restaurado com sucesso.',
                'data' => $media,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Registro não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao restaurar o registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    } 
    

    /**
     * Hard Remove the specified resource from storage.
     */    
    public function forceDelete(string $id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);
            
            $media->forceDelete();
    
            return response()->json([
                'message' => 'Registro excluído permanentemente com sucesso.',
                'data' => $media,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Registro não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir permanentemente o registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }     
}
