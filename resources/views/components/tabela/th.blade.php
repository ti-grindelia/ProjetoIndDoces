@props(['headers', 'nome'])

<div wire:click="ordenarPor('{{ $nome }}', '{{ $headers['ordenarDirecao'] == 'asc' ? 'desc' : 'asc' }}')"
     class="cursor-pointer">
    {{ $headers['label'] }}
    @if($headers['ordenarPelaColuna'] == $nome)
        <x-icon :name="$headers['ordenarDirecao'] == 'asc' ? 'o-chevron-up' : 'o-chevron-down'"
                class="h-3 w-3 ml-px"/>
    @endif
</div>
