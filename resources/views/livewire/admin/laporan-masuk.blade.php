<div>
    <div class="bg-white rounded-lg overflow-hidden dark:bg-dark-eval-1 shadow relative">
        <div class="flex items-center gap-2  px-3 py-3 bg-gray-50 text-right dark:bg-dark-eval-2">
            <h2 class="text-xl text-left font-semibold flex-grow">
                Daftar Laporan Masuk
            </h2>
        </div>
        <div class="py-3 px-3">
            <div>

                <x-default-input label="Pencarian" name="q"/>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-nowrap rounded overflow-hidden"
                       wire:loading.class.delay="opacity-50">
                    <thead class="bg-secondary text-gray-100 font-bold">
                    <tr class="text-left font-bold bg-red-700">
                        <td class="px-3 py-2 text-sm">#</td>
                        <td class="px-3 py-2 text-sm">No. Laporan</td>
                        <td class="px-3 py-2 text-sm">Label</td>
                        <td class="px-3 py-2 text-sm">Klasifikasi</td>
                        <td class="px-3 py-2 text-sm">Status</td>
                        <td class="px-3 py-2 text-sm">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($datas as $results)
                        <tr class="hover:bg-red-300 dark:hover:text-gray-900 dark:hover:bg-dark-eval-2 {{ ($loop->even ) ? "bg-red-100 dark:bg-dark-eval-3" : ""}}">
                            <td class="px-3 py-2 text-sm">{{ $loop->iteration + $datas->firstItem() - 1 }}</td>
                            <td class="px-3 py-2 text-sm">
                                {{ $results->no_laporan }}
                            </td><td class="px-3 py-2 text-sm">
                                {{ $results->label }}
                            </td>
                            <td class="px-3 py-2 text-sm">
                                {{ $results->klasifikasi->nama }}
                            </td>
                            <td class="px-3 py-2 text-sm">
                                {{$results->status()}}
                            </td>
                            <td class="px-3 py-2 text-sm">
                                <div class="flex gap-2">
                                    <x-button wire:click="show({{ $results->id }})" variant="primary" size="sm">
                                        Detail
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-sm text-gray-500 py-3 px-3 bg-gray-200">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="flex justify-between items-center mt-3">
                    <div>
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-tall-crud-dialog-modal wire:model.live="modal">
        <x-slot:title>
            <div class="flex items-center gap-2">
                <span class="text-lg font-semibold">Detail Laporan</span>
            </div>
        </x-slot:title>
        <x-slot:content>
            @if($detail)
                <div class="grid grid-cols-1 gap-4 justify-content-end items-end">
                    <div>
                        <x-label>Nomor Laporan</x-label>
                        <span class="text-gray-500">{{ $detail->no_laporan }}</span>
                    </div>
                    <div>
                        <x-label>Judul Laporan</x-label>
                        <span class="text-gray-500">{{ $detail->label }}</span>
                    </div>
                    <div>
                        <x-label>Deskripsi Laporan</x-label>
                        <span class="text-gray-500">{{ $detail->deskripsi }}</span>
                    </div>
                    <x-default-input type="file" name="items.bukti" label="Bukti Laporan" :items="$items"/>
                </div>
            @endif
        </x-slot:content>
        <x-slot:footer>
            <x-button wire:click="$set('modal', false)" variant="secondary" class="uppercase">
                Tutup
            </x-button>
            <x-button wire:click="update(3)" variant="danger" class="uppercase">
                    Tandai Selesai
            </x-button>
            <x-button wire:click="update(1)" variant="primary" class="uppercase">
                Tindak Lanjuti
            </x-button>
        </x-slot:footer>
    </x-tall-crud-dialog-modal>
</div>
