<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Usuario;
use App\Services\PedidoListarMateriasService;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPdfController extends Controller
{
    public function imprimir(Pedido $pedido)
    {
        $dados = app(PedidoListarMateriasService::class)->calcular($pedido);

        $user = Usuario::find($pedido->UsuarioID);

        $pdf = Pdf::loadView('pdf.pedido', [
            'user' => $user,
            'pedido' => $pedido,
            'materiasPrimas' => $dados['materiasPrimas']
        ])->setPaper('a4');

        return $pdf->stream("pedido-{$pedido->PedidoID}.pdf");
    }
}
