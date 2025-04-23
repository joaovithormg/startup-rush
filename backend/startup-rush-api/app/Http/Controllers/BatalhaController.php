<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Startup;
use App\Models\Batalha;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BatalhaController extends Controller
{
    public function pendentes()
    {
        $batalhas = Batalha::where('concluida', false)
            ->with(['startup1', 'startup2'])
            ->get();
        
        return response()->json($batalhas);
    }

    public function resolver($id, Request $request)
{
    return DB::transaction(function() use ($id, $request) {
        $batalha = Batalha::findOrFail($id);
        
        if ($batalha->concluida) {
            return response()->json(['erro' => 'Essa batalha já foi concluída.'], 400);
        }

        $faseAtual = $batalha->fase;

        $startup1 = Startup::find($batalha->startup_1_id);
        $startup2 = Startup::find($batalha->startup_2_id);

        $p1 = $batalha->pontos_startup_1;
        $p2 = $batalha->pontos_startup_2;

        $eventos = [];

        if ($request->input('startup1_pitch')) {
            $p1 += 6;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup1->id,
                'tipo' => 'pitch',
                'pontos' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup1_bugs')) {
            $p1 -= 4;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup1->id,
                'tipo' => 'bugs',
                'pontos' => -4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup1_tracao')) {
            $p1 += 3;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup1->id,
                'tipo' => 'tracao',
                'pontos' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup1_investidor')) {
            $p1 -= 6;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup1->id,
                'tipo' => 'investidor',
                'pontos' => -6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup1_fake_news')) {
            $p1 -= 8;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup1->id,
                'tipo' => 'fake_news',
                'pontos' => -8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup2_pitch')) {
            $p2 += 6;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup2->id,
                'tipo' => 'pitch',
                'pontos' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup2_bugs')) {
            $p2 -= 4;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup2->id,
                'tipo' => 'bugs',
                'pontos' => -4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup2_tracao')) {
            $p2 += 3;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup2->id,
                'tipo' => 'tracao',
                'pontos' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup2_investidor')) {
            $p2 -= 6;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup2->id,
                'tipo' => 'investidor',
                'pontos' => -6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if ($request->input('startup2_fake_news')) {
            $p2 -= 8;
            $eventos[] = [
                'batalha_id' => $batalha->id,
                'startup_id' => $startup2->id,
                'tipo' => 'fake_news',
                'pontos' => -8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        if (count($eventos) > 0) {
            \App\Models\Evento::insert($eventos);
        }

        $sharkFight = false;
        $sharkFightVencedor = null;

        if ($p1 === $p2) {
            $sharkFight = true;
            $random = rand(1, 2);
            if ($random === 1) {
                $p1 += 2;
                $vencedor = 'startup_1';
                $sharkFightVencedor = $startup1->nome;
            } else {
                $p2 += 2;
                $vencedor = 'startup_2';
                $sharkFightVencedor = $startup2->nome;
            }
        } else {
            $vencedor = $p1 > $p2 ? 'startup_1' : 'startup_2';
        }

        if ($vencedor === 'startup_1') {
            $startup1->pontos += 30;
            $startup1->fase = $faseAtual + 1;
            $startup1->save();
            $batalha->vencedor_id = $startup1->id;
            $nomeVencedor = $startup1->nome;
        } else {
            $startup2->pontos += 30;
            $startup2->fase = $faseAtual + 1;
            $startup2->save();
            $batalha->vencedor_id = $startup2->id;
            $nomeVencedor = $startup2->nome;
        }

        $batalha->pontos_startup_1 = $p1;
        $batalha->pontos_startup_2 = $p2;
        $batalha->concluida = true;
        $batalha->save();

        $batalhasPendentes = Batalha::where('fase', $faseAtual)
            ->where('concluida', false)
            ->count();

        $resposta = [
            'mensagem' => 'Batalha resolvida!',
            'vencedor' => $nomeVencedor,
            'pontuacao_final' => [
                $startup1->nome => $p1,
                $startup2->nome => $p2
            ],
            'houve_shark_fight' => $sharkFight
        ];

        if ($sharkFight) {
            $resposta['shark_fight_vencedor'] = $sharkFightVencedor;
            $resposta['shark_fight_bonus'] = 2;
        }

        if ($batalhasPendentes === 0) {
            $torneioController = new TorneioController();
            $resultadoAvanco = $torneioController->avancarFase($faseAtual);
            $resposta['fase_avancada'] = true;
            $resposta['dados_proxima_fase'] = $resultadoAvanco;
        }

        $resposta['batalhas_pendentes'] = Batalha::where('concluida', false)
            ->with(['startup1', 'startup2'])
            ->get();

        return response()->json($resposta);
    });
}

}