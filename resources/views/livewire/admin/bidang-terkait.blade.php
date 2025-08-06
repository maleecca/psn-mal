<div>
    <div class="bg-white rounded-lg overflow-hidden dark:bg-dark-eval-1 shadow relative">
        <div class="flex items-center gap-2  px-3 py-3 bg-gray-50 text-right dark:bg-dark-eval-2">
            <h2 class="text-xl text-left font-semibold flex-grow">
                Daftar Bidang Terkait
            </h2>
        </div>
        <div class="py-3 px-3">
            <div class="text-right">
                <x-button wire:click="openCreate()">
                    Buat Bidang Terkait
                </x-button>
            </div>
            <div>
                <x-default-input label="Pencarian" name="q"/>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-nowrap rounded overflow-hidden"
                       wire:loading.class.delay="opacity-50">
                    <thead class="bg-secondary text-gray-100 font-bold">
                    <tr class="text-left font-bold bg-red-700">
                        <td class="px-3 py-2 text-sm">#</td>
                        <td class="px-3 py-2 text-sm">Nama</td>
                        <td class="px-3 py-2 text-sm">Daftar Petugas</td>
                        <td class="px-3 py-2 text-sm w-10">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($datas as $results)
                        <tr class="hover:bg-red-300 dark:hover:text-gray-900 dark:hover:bg-dark-eval-2 {{ ($loop->even ) ? "bg-red-100 dark:bg-dark-eval-3" : ""}}">
                            <td class="px-3 py-2 text-sm">{{ $loop->iteration + $datas->firstItem() - 1 }}</td>
                            <td class="px-3 py-2 text-sm">
                                {{ $results->nama }}
                            </td>
                            <td class="px-3 py-2 text-sm ">
                                <x-secondary-button class="text-xs py-2 "
                                                    wire:click="openEditPetugas({{$results->id}})">
                                    edit
                                </x-secondary-button>
                                {!! $results->petugasList() !!}
                            </td>
                            <td class="px-3 py-2 text-sm">
                                <x-secondary-button wire:click="openEdit({{$results->id}})">
                                    edit
                                </x-secondary-button>
                                <x-secondary-button wire:click="delete({{$results->id}})">
                                    hapus
                                </x-secondary-button>
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
            </div>
            <div class="my-2">
                {!! $datas->links() !!}
            </div>
        </div>
    </div>
    <x-tall-crud-dialog-modal wire:model="modal">
        <x-slot name="title">
            Bidang Terkait
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 gap-2">
                <x-default-input name="items.nama" label="Nama" type="text"/>
                <x-default-input name="items.deskripsi" label="Deskripsi" type="textarea"/>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('modal',false)">
                tutup
            </x-secondary-button>
            <x-button wire:click="store">
                Buat
            </x-button>
        </x-slot>
    </x-tall-crud-dialog-modal>
    <x-tall-crud-dialog-modal wire:model="bidang_terkait_id">
        <x-slot name="title">
            Bidang Tindak Lanjut
        </x-slot>
        <x-slot name="content">
            <div>
                <div class="grid grid-cols-1 gap-2">
                    <x-button wire:click="addBidangPetugas">
                        Tambah Petugas
                    </x-button>
                    @foreach($bidang_terkait_array as $i)
                        <div class="flex items-center gap-2">
                            <div class="flex-grow">
                                <x-default-input
                                    name="items.petugas_id.{{$i}}"
                                    label="Petugas {{$i}}"
                                    :option="$petugas"
                                    type="select"
                                />
                            </div>
                            <x-secondary-button wire:click="deleteBidangPetugas({{$i}})">
                                hapus
                            </x-secondary-button>
                        </div>

                    @endforeach
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('bidang_terkait_id',null)">
                tutup
            </x-secondary-button>
            <x-button wire:click="storeBidangPetugas">
                Buat
            </x-button>
        </x-slot>
    </x-tall-crud-dialog-modal>

</div>
