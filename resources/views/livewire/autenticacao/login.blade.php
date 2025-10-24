<div class="mx-auto">
    <div class="mb-4 text-center">
        <label class="text-3xl font-semibold text-blue-600 tracking-wide drop-shadow-sm">
            Indústria de <span class="text-blue-500">Doces</span>
        </label>
    </div>

    <x-card title="Login" shadow class="w-[450px]">
        @if($errors->hasAny(['credenciaisInvalidas']))
            <x-alert icon="o-exclamation-triangle" class="alert-warning mb-4 text-sm">
                @error('credenciaisInvalidas')
                    <span>{{ $message }}</span>
                @enderror
            </x-alert>
        @endif

        <x-form wire:submit="tentarLogar">
            <x-input label="Email" wire:model="email"/>
            <x-input label="Senha" wire:model="senha" type="password"/>

            <x-slot:actions>
                <div class="w-full flex items-center justify-between">
                    <a href="{{ route('auth.registro') }}" wire:navigate class="link text-blue-600">
                        Quero criar uma conta
                    </a>
                    <div>
                        <x-button label="Login" class="bg-blue-600 text-white" type="submit" spinner="submit"/>
                    </div>
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
