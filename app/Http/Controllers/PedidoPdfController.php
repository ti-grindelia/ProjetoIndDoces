<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Usuario;
use App\Services\PedidoListarMateriasService;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPdfController extends Controller
{
    public function imprimirMateria(Pedido $pedido)
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $user = Usuario::find($pedido->UsuarioID);

        $pdf = Pdf::loadView('pdf.materia', [
            'user' => $user,
            'pedido' => $pedido,
            'materiasPrimas' => $dados['materiasPrimas']
        ])->setPaper('a4');

        return $pdf->stream("pedido-$pedido->PedidoID.pdf");
    }

    public function imprimirPedidoReceita(Pedido $pedido)
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $totais = $this->calcularTotais($dados['itens']);

        $user = Usuario::find($pedido->UsuarioID);

        $pdf = Pdf::loadView('pdf.pedidoComReceita', [
            'user' => $user,
            'pedido' => $pedido,
            'produtos' => $dados['itens'],
            'materiasPrimas' => $dados['materiasPrimas'],
            'totais' => $totais,
        ])->setPaper('a4');

        return $pdf->stream("pedido-$pedido->PedidoID.pdf");
    }

    public function imprimirPedidoSemReceita(Pedido $pedido)
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $totais = $this->calcularTotais($dados['itens']);

        $user = Usuario::find($pedido->UsuarioID);

        $pdf = Pdf::loadView('pdf.pedidoSemReceita', [
            'user' => $user,
            'pedido' => $pedido,
            'produtos' => $dados['itens'],
            'totais' => $totais,
        ])->setPaper('a4');

        return $pdf->stream("pedido-$pedido->PedidoID.pdf");
    }

    public function imprimirPedidoSimples(Pedido $pedido)
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $totais = [
            'qtde' => collect($dados['itens'])->sum('Quantidade'),
            'custoInd' => collect($dados['itens'])->sum(fn($i) => $i['Produto']['CustoIndustrializacao']),
        ];

        $user = Usuario::find($pedido->UsuarioID);

        $pdf = Pdf::loadView('pdf.pedidoSimples', [
            'user' => $user,
            'pedido' => $pedido,
            'produtos' => $dados['itens'],
            'totais' => $totais,
        ])->setPaper('a4');

        return $pdf->stream("pedido-$pedido->PedidoID.pdf");
    }

    private function calcularTotais(array $itens): array
    {
        return [
            'qtde' => collect($itens)->sum('Quantidade'),
            'custoMP' => collect($itens)->sum(fn($i) => $i['Produto']['CustoMateriaPrima']),
            'custoInd' => collect($itens)->sum(fn($i) => $i['Produto']['CustoIndustrializacao']),
            'custoTotal' => collect($itens)->sum(fn($i) => $i['Produto']['CustoTotal']),
            'valorMva' => collect($itens)->sum(fn($i) => $i['Produto']['ValorMVA']),
            'valorIcms' => collect($itens)->sum(fn($i) => $i['Produto']['ValorICMS']),
            'custo' => collect($itens)->sum('CustoTotal'),
        ];
    }
}
