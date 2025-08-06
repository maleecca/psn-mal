<div class="w-full min-h-screen p-0 m-0 ">
    <div class="min-h-screen flex flex-col justify-center items-center px-3">
        <div class="grid grid-cols-1 gap-2 md:w-[40em] w-full">
            <div class="bg-white shadow py-5 px-5 ">
                <div class="text-black my-5 border-b py-5">
                    <div class="flex justify-center">
                        <a href="/" class="flex  gap-3">
                            <x-application-logo class="w-[4em] h-[4em]"/>
                            <span class="text-2xl font-mono whitespace-pre ">LaporinAja</span>
                        </a>
                    </div>
                    <div class="text-left mt-4">
                        <a href="{{route('buat-laporan')}}">
                            <x-button class="w-full">
                                BUAT LAPORAN
                            </x-button>
                        </a>
                    </div>
                </div>
                <div class="flex gap-2 justify-content-end items-end">
                    <x-default-input class="flex-grow" type="text" name="search" label="Cek Laporan Kamu"/>
                    <div class="text-right py-4">
                        <x-button wire:click="searchData">
                            Cek
                        </x-button>
                    </div>
                </div>
            </div>
            @if($laporan)
                <div class="bg-white py-3">
                    <div class="text-black my-5 border-b py-5">
                        <div class="flex justify-center">
                            <a href="/" class="flex  gap-3">
                                <x-application-logo class="w-[4em] h-[4em]"/>
                                <span class="text-2xl font-mono whitespace-pre ">DETAIL LAPORAN</span>
                            </a>
                        </div>
                    </div>
                    <div class="max-w-md mx-auto pb-10">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold">Nomor Laporan</h2>
                            <p class="text-gray-500">{{$laporan->no_laporan}}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold">Judul Laporan</h2>
                            <p class="text-gray-500">{{$laporan->label}}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold">Status</h2>
                            <p class="text-gray-500">{{\App\Models\Laporan::STATUS[$laporan->status]}}</p>
                        </div>
                        <hr class="my-5">
                        <ol class="relative border-l border-red-600">
                            @foreach($laporan->progress() as $progress)
                                @if($progress['status'] == 1)
                                    <li class="mb-10 ml-6">
      <span
          class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 ring-8 ring-white">
        <svg class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                clip-rule="evenodd"/>
        </svg>
      </span>
                                        <h3 class="font-medium leading-tight">Bidang
                                            : {{$progress['bidang_terkait']}}</h3>
                                        @forelse($progress['tindakan'] as $tindakan)
                                            <p class="text-sm text-gray-500">{{$tindakan->dokumen}}</p>
                                            <p class="text-sm text-gray-500">{{$tindakan->created_at}}</p>
                                        @empty
                                            <p class="text-sm text-gray-500">Belum ada tindakan</p>
                                        @endforelse
                                    </li>
                                @else
                                    <li class="mb-10 ml-6">

                        <span
                            class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 border-gray-300 ring-8 ring-white"></span>
                                        <h3 class="font-medium leading-tight">Bidang
                                            : {{$progress['bidang_terkait']}}</h3>
                                        @forelse($progress['tindakan'] as $tindakan)
                                            <p class="text-sm text-gray-500">{{$tindakan->dokumen}}</p>
                                            <p class="text-sm text-gray-500">{{$tindakan->created_at}}</p>
                                        @empty
                                            <p class="text-sm text-gray-500">Belum ada tindakan</p>
                                        @endforelse
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 text-white">
        
    </div>
</div>
