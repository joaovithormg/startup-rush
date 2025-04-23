<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        return response()->json(Startup::all());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'slogan' => 'required|string|max:255',
            'ano_fundacao' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $totalStartups = Startup::count();
        if ($totalStartups >= 8) {
            return response()->json(['erro' => 'Limite máximo de 8 startups atingido.'], 400);
        }

        $startup = Startup::create([
            'nome' => $request->nome,
            'slogan' => $request->slogan,
            'ano_fundacao' => $request->ano_fundacao,
            'pontos' => 70,
        ]);

        $restantes = 8 - ($totalStartups + 1);
        return response()->json([
            'mensagem' => 'Startup cadastrada com sucesso!',
            'startup' => $startup,
            'restantes_para_8' => $restantes,
        ], 201);
    }
}