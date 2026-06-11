<?php

namespace App\Http\Controllers;

use App\Exports\MateriaPrimasExport;
use App\Exports\PedidoProdutosExport;
use App\Exports\PedidoProdutosSimplesExport;
use App\Models\Pedido;
use App\Services\PedidoListarMateriasService;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PedidoExcelController extends Controller
{
    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportarMateria(Pedido $pedido): BinaryFileResponse
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        return Excel::download(
            new MateriaPrimasExport($dados['materiasPrimas'], $pedido->PedidoID),
            "pedido-$pedido->PedidoID-materias-primas.xlsx"
        );
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportarPedidoReceita(Pedido $pedido): BinaryFileResponse
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);
        $totais = $this->calcularTotais($dados['itens']);

        return Excel::download(
            new PedidoProdutosExport($dados['itens'], $totais, comReceita: true),
            "pedido-$pedido->PedidoID-produtos-receita.xlsx"
        );
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportarPedidoSemReceita(Pedido $pedido): BinaryFileResponse
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);
        $totais = $this->calcularTotais($dados['itens']);

        return Excel::download(
            new PedidoProdutosExport($dados['itens'], $totais, comReceita: false),
            "pedido-$pedido->PedidoID-produtos.xlsx"
        );
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportarPedidoSimples(Pedido $pedido): BinaryFileResponse
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $totais = [
            'qtde' => collect($dados['itens'])->sum('Quantidade'),
            'custoInd' => collect($dados['itens'])->sum(fn($i) => $i['Produto']['CustoIndustrializacao']),
        ];

        return Excel::download(
            new PedidoProdutosSimplesExport($dados['itens'], $totais),
            "pedido-$pedido->PedidoID-produtos-simples.xlsx"
        );
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
