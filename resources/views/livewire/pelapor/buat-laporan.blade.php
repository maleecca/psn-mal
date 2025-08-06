<div class="w-full {{auth()->check() ? '':'min-h-screen'}} p-0 m-0 ">
    <div class="{{auth()->check() ? '':'min-h-screen px-3'}} flex flex-col justify-center items-center">
        <div class="bg-white shadow {{auth()->check() ? '':'md:w-[40em] '}}  w-full py-5 px-5">
            @if(!$laporan)
                <div class="text-black my-5 border-b py-5">
                    <div class="flex justify-center">
                        <a href="/" class="flex  gap-3">
                            <x-application-logo class="w-[4em] h-[4em]"/>
                            <span class="text-2xl font-mono whitespace-pre ">BUAT LAPORAN</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 justify-content-end items-end">
                    {!! \App\Helpers\Form::generateSelectOptions($items,App\Models\Klasifikasi::get()) !!}
                    <x-default-input class="flex-grow" type="email" name="items.email" label="Email"/>
                    <x-default-input class="flex-grow" type="text" name="items.label" label="Judul Laporan"/>
                    <x-default-input class="flex-grow" type="textarea" name="items.deskripsi"
                                     label="Deskripsi Laporan"/>
                    <x-default-input type="file" name="items.bukti" label="Bukti Laporan" :items="$items"/>
                </div>
                <div class="text-right py-4">
                    <x-button wire:click="store" class="uppercase py-2.5">
                        BUAT LAPORAN
                    </x-button>
                </div>
            @else

                <div class="text-black my-5 border-b py-5">
                    <div class="flex justify-center">
                        <a href="/" class="flex  gap-3">
                            <x-application-logo class="w-[4em] h-[4em]"/>
                            <span class="text-2xl font-mono whitespace-pre ">LAPORAN BERHASIL DIBUAT</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 justify-content-end items-end">
                    <div class="text-center">
                        <h3>No. Laporan : <span class="text-green-500">{{$laporan->no_laporan}}</span></h3>
                        <p class="text-gray-600">Terima kasih telah melaporkan. Laporan Anda akan segera diproses.</p>
                    </div>
                    <div class="text-center mt-4">
                        <x-button wire:click="$set('laporan',null)" class="uppercase py-2.5">
                            Kembali ke Beranda
                        </x-button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(!auth()->check())
        <div class="absolute bottom-0 left-0 right-0 text-white">
            
        </div>
    @endif
</div>
