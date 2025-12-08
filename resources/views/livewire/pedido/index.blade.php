<div>
    <x-header title="Pedidos" separator/>

    <div class="flex flex-row gap-4 mb-6">
        <div class="w-1/8 text-md font-medium">
            <x-select
                wire:model.live="empresaFiltro"
                :options="array_merge(
                    [['id' => 0, 'name' => 'Ambas']],
                    $empresas
                )"
                option-label="name"
                option-value="id"
            />
        </div>
        <div class="w-1/8 text-md font-medium">
            <x-select wire:model.live="statusFiltro"
                :options="[
                    ['id'=>'Todos','name'=>'Todos'],
                    ['id'=>'Abertos','name'=>'Abertos'],
                    ['id'=>'Producao','name'=>'Produção'],
                    ['id'=>'Finalizados','name'=>'Finalizados'],
                    ['id'=>'Cancelados','name'=>'Cancelados']
                ]"
            />
        </div>
        <div class="w-1/8 text-md font-medium">
            <x-datetime wire:model.live="dataFiltro"/>
        </div>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_Pedido', $headers)
        <x-tabela.th :$headers nome="pedido"/>
        @endscope

        @scope('header_Empresa', $headers)
        <x-tabela.th :$headers nome="empresa"/>
        @endscope

        @scope('header_Status', $headers)
        <x-tabela.th :$headers nome="status"/>
        @endscope

        @scope('header_Data', $headers)
        <x-tabela.th :$headers nome="data"/>
        @endscope

        @scope('header_Custo', $headers)
        <x-tabela.th :$headers nome="custo"/>
        @endscope

        @scope('cell_status_formatado', $pedido)
        @dump($pedido->status_formatado)
        @if($pedido->status_formatado == 'Aberto')
            <x-badge :value="$pedido->status_formatado" class="badge-warning badge-soft"/>
        @elseif($pedido->status_formatado == 'Produção')
            <x-badge :value="$pedido->status_formatado" class="badge-info badge-soft"/>
        @elseif($pedido->status_formatado == 'Finalizado')
            <x-badge :value="$pedido->status_formatado" class="badge-success badge-soft"/>
        @elseif($pedido->status_formatado == 'Cancelado')
            <x-badge :value="$pedido->status_formatado" class="badge-error badge-soft"/>
        @endif
        @endscope

        @scope('actions', $pedido)
        <div class="flex items-center space-x-2">
            <div class="tooltip tooltip-left" data-tip="Visualizar Pedido">
                <x-button
                    id="visualizar-btn-{{ $pedido->PedidoID }}"
                    wire:key="visualizar-btn-{{ $pedido->PedidoID }}"
                    icon="o-eye"
                    @click="$dispatch('pedido::visualizar', { id: {{ $pedido->PedidoID }} })"
                    spinner class="btn-sm btn-primary"
                />
            </div>

            @unless($pedido->Status == 'Cancelado' || $pedido->Status == 'Finalizado' || $pedido->Status == 'Producao')
                <div class="tooltip tooltip-left" data-tip="Cancelar Pedido">
                    <x-button
                        id="cancelar-btn-{{ $pedido->PedidoID }}"
                        wire:key="cancelar-btn-{{ $pedido->PedidoID }}"
                        icon="o-x-circle"
                        @click="$dispatch('pedido::cancelar', { id: {{ $pedido->PedidoID }} })"
                        spinner class="btn-sm btn-error"
                    />
                </div>
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:pedido.atualizar/>
    <livewire:pedido.cancelar/>
</div>
