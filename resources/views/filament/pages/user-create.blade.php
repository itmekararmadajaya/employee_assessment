<x-filament-panels::page>
    <div>
        {{$this->form}}
        <div class="mt-5">
            </a>
            <x-filament::button wire:click="create">
                Create
            </x-filament::button>
            <x-filament::button wire:click="createAndCreateAnother" color="gray">
                Create & create another
            </x-filament::button>
            <a href="{{route('filament.admin.resources.users.index')}}">
                <x-filament::button color="gray">
                Cancel
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
