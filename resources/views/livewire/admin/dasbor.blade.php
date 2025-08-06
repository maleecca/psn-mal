<div class="grid md:grid-cols-3 grid-cols-1 gap-2">
    @foreach($datas as $data)
        <div class="flex gap-5 bg-white p-3 shadow rounded dark:bg-dark-eval-1">
            @svg($data['icon'], 'h-12 w-12 text-gray-400')
            <div class="ml-2 flex-grow">
                <div class="text-sm text-gray-500">{{ $data['label'] }}</div>
                <div class="text-2xl font-semibold">{!! $data['value'] !!}</div>
            </div>
            <x-button wire:click="download('{{ $data['label'] }}')">
                @svg('heroicon-o-cloud-download', 'h-5 w-5')
            </x-button>
        </div>
    @endforeach
</div>
