<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Startup;
use App\Models\Batalha;
use Illuminate\Http\Request;

class TorneioController extends Controller
{
    public function iniciar()
    {
        // Verificar se já existe alguma batalha (torneio já iniciado)
        $batalhasExistentes = Batalha::count();
        if ($batalhasExistentes > 0) {
            return response()->json(['erro' => 'O torneio já foi iniciado.'], 400);
        }

        $startups = Startup::all();

        $total = $startups->count();

        if ($total < 4 || $total > 8) {
            return response()->json(['erro' => 'É necessário entre 4 e 8 startups para iniciar o torneio.'], 400);
        }

        if ($total % 2 !== 0) {
            return response()->json(['erro' => 'O número de startups deve ser par.'], 400);
        }

        // Embaralha as startups
        $startupsEmbaralhadas = $startups->shuffle()->values();

        // Define a fase inicial para todas as startups
        foreach ($startups as $startup) {
            $startup->fase = 1;
            $startup->save();
        }

        // Par de startups por batalha
        $fase = 1;
        for ($i = 0; $i < $total; $i += 2) {
            Batalha::create([
                'startup_1_id' => $startupsEmbaralhadas[$i]->id,
                'startup_2_id' => $startupsEmbaralhadas[$i + 1]->id,
                'pontos_startup_1' => $startupsEmbaralhadas[$i]->pontos,
                'pontos_startup_2' => $startupsEmbaralhadas[$i + 1]->pontos,
                'fase' => $fase
            ]);
        }

        return response()->json(['mensagem' => 'Torneio iniciado com sucesso!', 
        'batalhas_geradas' => $total / 2]);
    }

    public function avancar(Request $request)
    {
        $batalhasNaFase = Batalha::where('fase', $request->fase)
                                 ->where('concluida', false)
                                 ->get();
    
        if ($batalhasNaFase->count() > 0) {
            return response()->json(['erro' => 'Ainda existem batalhas pendentes nesta fase.'], 400);
        }
        
        // Usar o método interno para avançar
        $resultado = $this->avancarFase($request->fase);
        
        // Se houver um erro, retornar como resposta de erro
        if (isset($resultado['erro'])) {
            return response()->json(['erro' => $resultado['erro']], 400);
        }
        
        return response()->json($resultado);
    }

// Adicione este método ao TorneioController
public function avancarFase($fase)
{
    // Pegar apenas as startups vencedoras da fase atual
    $startupsVencedoras = Startup::whereIn('id', function($query) use ($fase) {
        $query->select('vencedor_id')
              ->from('batalhas')
              ->where('fase', $fase);
    })->get();
    
    if ($startupsVencedoras->count() === 1) {
        // Declarar a startup campeã do torneio
        $campea = $startupsVencedoras->first();
        
        return [
            'mensagem' => 'Torneio encerrado.',
            'vencedora' => $campea->nome,
            'pontos' => $campea->pontos,
            'torneio_finalizado' => true
        ];
    }
    
    if ($startupsVencedoras->count() < 2) {
        return [
            'erro' => 'Número insuficiente de startups para formar batalhas.',
            'avanco_falhou' => true
        ];
    }
    
    // Criar batalhas para a próxima fase
    $this->sorteioDeBatalhas($startupsVencedoras, $fase + 1);
    
    return [
        'mensagem' => 'Fase avançada com sucesso.',
        'nova_fase' => $fase + 1,
        'startups_na_proxima_fase' => $startupsVencedoras->count(),
        'batalhas_proxima_fase' => floor($startupsVencedoras->count() / 2)
    ];
}

    private function sorteioDeBatalhas($startups, $fase)
    {
        $startups = $startups->shuffle();

        if ($startups->count() < 2) {
            return;
        }

        $batalhas = [];

        for ($i = 0; $i < $startups->count(); $i += 2) {
            if (!isset($startups[$i + 1])) break;

            $batalhas[] = [
                'startup_1_id' => $startups[$i]->id,
                'startup_2_id' => $startups[$i + 1]->id,
                'pontos_startup_1' => $startups[$i]->pontos,
                'pontos_startup_2' => $startups[$i + 1]->pontos,
                'fase' => $fase,
                'concluida' => false,
            ];
        }

        Batalha::insert($batalhas);
    }

    public function status()
    {
        $faseAtual = Batalha::max('fase') ?? 0;
        
        if ($faseAtual === 0) {
            return response()->json([
                'mensagem' => 'O torneio ainda não foi iniciado.',
                'torneio_iniciado' => false
            ]);
        }

        $batalhas = Batalha::where('fase', $faseAtual)
            ->with(['startup1', 'startup2'])
            ->get();

        $startups = Startup::orderBy('fase', 'desc')->get();

        // Verificar se o torneio foi finalizado - uma única startup com fase maior
        $maxFase = Startup::max('fase') ?? 0;
        $startupsFinalistas = Startup::where('fase', $maxFase)->get();
        $torneioFinalizado = $startupsFinalistas->count() === 1;
        
        if ($torneioFinalizado) {
            $campea = $startupsFinalistas->first();
            return response()->json([
                'mensagem' => 'O torneio já foi finalizado.',
                'fase_final' => $faseAtual,
                'vencedora' => [
                    'id' => $campea->id,
                    'nome' => $campea->nome,
                    'pontos' => $campea->pontos,
                    'slogan' => $campea->slogan,
                    'ano_fundacao' => $campea->ano_fundacao
                ],
                'torneio_finalizado' => true
            ]);
        }

        $startupsVivas = Startup::where('fase', $faseAtual)->get();

        return response()->json([
            'fase_atual' => $faseAtual,
            'batalhas' => $batalhas,
            'startups' => $startups,
            'startups_ativas' => $startupsVivas->count(),
            'batalhas_pendentes' => Batalha::where('fase', $faseAtual)->where('concluida', false)->count(),
            'torneio_iniciado' => true,
            'torneio_finalizado' => false
        ]);
    }

    public function relatorioDetalhado()
    {
        try {
            $startups = Startup::withCount([
                'pitches',
                'bugs',
                'tracoes',
                'investidores as num_investidores_irritados_count', 
                'fake_news as num_fake_news_count', 
            ])->orderBy('pontos', 'desc')->get();
    
            $maxFase = Startup::max('fase') ?? 0;
            $startupsFinalistas = Startup::where('fase', $maxFase)->get();
            $torneioFinalizado = ($startupsFinalistas->count() === 1 && $maxFase > 1);
            $campeao = null;
    
            if ($torneioFinalizado) {
                $campeao = $startups->where('id', $startupsFinalistas->first()->id)->first();
            } else if ($startups->count() > 0) {
                $campeao = $startups->first();
            }
    
            $formattedStartups = $startups->map(function ($startup) {
                return [
                    'id' => $startup->id,
                    'nome' => $startup->nome,
                    'slogan' => $startup->slogan ?? '',
                    'pontos' => (int)$startup->pontos,
                    'num_pitches' => (int)$startup->pitches_count,
                    'num_bugs' => (int)$startup->bugs_count,
                    'num_tracoes' => (int)$startup->tracoes_count,
                    'num_investidores_irritados' => (int)($startup->num_investidores_irritados_count ?? 0),
                    'num_fake_news' => (int)($startup->num_fake_news_count ?? 0), 
                ];
            })->values()->all();
    
            $formattedCampeao = null;
            if ($campeao) {
                $formattedCampeao = [
                    'id' => $campeao->id,
                    'nome' => $campeao->nome,
                    'slogan' => $campeao->slogan ?? '',
                    'pontos' => (int)$campeao->pontos,
                    'num_pitches' => (int)$campeao->pitches_count,
                    'num_bugs' => (int)$campeao->bugs_count,
                    'num_tracoes' => (int)$campeao->tracoes_count,
                    'num_investidores_irritados' => (int)($campeao->num_investidores_irritados_count ?? 0),
                    'num_fake_news' => (int)($campeao->num_fake_news_count ?? 0), 
                ];
            }
    
            return response()->json([
                'startups' => $formattedStartups,
                'campeao' => $formattedCampeao,
                'torneioFinalizado' => $torneioFinalizado
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resetar()
    {
        DB::table('eventos')->truncate();
        DB::table('batalhas')->truncate();
        
        Startup::query()->update(['fase' => null, 'pontos' => 70]);
        
        return response()->json(['mensagem' => 'Torneio resetado com sucesso!']);
    }

}