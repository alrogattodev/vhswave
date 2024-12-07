<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json([
            'success' => true,
            'data' => $clients,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:clients,email',
                'phone' => 'required|string|max:16',
            ],
            [
                'email.email' => 'O e-mail precisa ser um endereço de e-mail válido',
                'email.unique' => 'Já existe um cliente com este e-mail.',
            ]);

            $client = Client::create($validated);
            return response()->json($client, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o registro',
                'error' => $e->getMessage(),
            ]);
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
            $client = Client::findOrFail($id);
            return response()->json($client);
        } catch (ModuleNotFoundException $e) {
            return response()->json([
                'message' => 'O registro não existe ou já foi excluído.'
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:clients,email',
                'phone' => 'required|string|max:18',
            ],
            [
                'email.email' => 'O e-mail precisa ser um endereço de e-mail válido',
                'email.unique' => 'Já existe um cliente com este e-mail.',
            ]);

            $client = Client::findOrFail($id);
            $client->update($validated);

            return response()->json($client, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$id) {
            return response()->json([
                'message' => 'O ID do registro é obrigatório.'
            ], 400);
        }

        try {
            $client = Media::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'O registro não existe ou já foi excluído'
            ], 404);
        }

        try {
            $client->delete();
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
            $client = Media::withTrashed()->findOrFail($id);
    
            if (!$client->trashed()) {
                return response()->json([
                    'message' => 'O registro não está excluído.'
                ], 400);
            }
    
            $client->restore();
    
            return response()->json([
                'message' => 'Registro restaurado com sucesso.',
                'data' => $client,
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
            $client = Media::withTrashed()->findOrFail($id);
            
            $client->forceDelete();
    
            return response()->json([
                'message' => 'Registro excluído permanentemente com sucesso.',
                'data' => $client,
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
