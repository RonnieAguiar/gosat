<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpf;
use App\Models\Instituicao;
use App\Models\Modalidade;
use App\Models\DetalheModalidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ConsultaCreditoController extends Controller
{
    public function consultar(Request $request)
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        $valorRequisitado = $request->input('valor');

        if (strlen($cpf) !== 11) {
            return response()->json(['erro' => 'CPF inválido'], 422);
        }

        DB::beginTransaction();
        try {
            $cpfModel = Cpf::firstOrCreate(['cpf' => $cpf]);

            $consulta = $cpfModel->consultas()->create([
                'consultado_em' => now(),
            ]);

            $dadosInstituicoes = $this->getInstituicoes($cpf);

            $ofertas = [];

            foreach ($dadosInstituicoes as $inst) {
                $instituicao = Instituicao::updateOrCreate(
                    ['id' => $inst['id']],
                    ['nome' => $inst['nome']]
                );

                foreach ($inst['modalidades'] as $mod) {
                    $modalidade = Modalidade::updateOrCreate(
                        [
                            'instituicao_id' => $instituicao->id,
                            'codigo' => $mod['cod'],
                        ],
                        ['nome' => $mod['nome']]
                    );

                    $detalhes = $this->getDetalhes($cpf, $instituicao->id, $mod['cod']);

                    DetalheModalidade::updateOrCreate([
                        'cpf_id' => $cpfModel->id,
                        'modalidade_id' => $modalidade->id,
                    ], [
                        'qnt_parcela_min' => $detalhes['QntParcelaMin'],
                        'qnt_parcela_max' => $detalhes['QntParcelaMax'],
                        'valor_min' => $detalhes['valorMin'],
                        'valor_max' => $detalhes['valorMax'],
                        'juros_mes' => $detalhes['jurosMes'],
                    ]);

                    $valorSolicitado = $detalhes['valorMax'] > $valorRequisitado ? $valorRequisitado : $detalhes['valorMax'];
                    $qntParcelas = $detalhes['QntParcelaMin'];
                    $jurosMes = $detalhes['jurosMes'];

                    $valorAPagar = $valorSolicitado * pow(1 + $jurosMes, $qntParcelas);

                    $ofertas[] = [
                        'instituicaoFinanceira' => $instituicao->nome,
                        'modalidadeCredito' => $modalidade->nome,
                        'valorDisponivel' => $valorSolicitado,
                        'qntParcelas' => $qntParcelas,
                        'taxaJuros' => $jurosMes,
                        'valorAPagar' => round($valorAPagar, 2),
                    ];
                }
            }

            DB::commit();

            $melhoresOfertas = collect($ofertas)
                ->sortBy('valorAPagar')
                ->values()
                ->take(3);

            return response()->json([
                'mensagem' => 'Consulta realizada com sucesso.',
                'melhores_ofertas' => $melhoresOfertas,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'erro' => 'Erro ao consultar créditos',
                'detalhe' => $e->getMessage(),
            ], 500);
        }
    }

    private function getInstituicoes(string $cpf)
    {
        $response = Http::post('https://dev.gosat.org/api/v1/simulacao/credito', [
            'cpf' => $cpf,
        ]);

        if ($response->failed()) {
            throw new \Exception('Erro ao consultar instituições: ' . $response->body());
        }

        return $response->json('instituicoes') ?? [];
    }

    private function getDetalhes(string $cpf, int $instituicaoId, string $codModalidade)
    {
        $response = Http::post('https://dev.gosat.org/api/v1/simulacao/oferta', [
            'cpf' => $cpf,
            'instituicao_id' => $instituicaoId,
            'codModalidade' => $codModalidade,
        ]);

        if ($response->failed()) {
            throw new \Exception('Erro ao consultar oferta: ' . $response->body());
        }

        return $response->json();
    }
}
