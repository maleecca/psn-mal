@props(['items','reset'])
<div class="{{$attributes['class']}} mb-4">
    @if($attributes['type']=='select')
        <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
        @php($wireChange = isset($reset) ? 'resetFormData(' . json_encode($reset) . ')' : null)
        <x-select id="{{$attributes['name']}}"
                  class="block mt-1 w-full dark:placeholder-gray-300"
                  :wire:change="$wireChange"
                  :name="$attributes['name']"
                  wire:model.change="{{$attributes['name']}}"
                  wire:key="{{$attributes['name']}}"
                  :disabled="@$attributes['disabled'] ?? false"
                  required>
            <option value="">{{__('Pilih')}} {{$attributes['label']}}</option>
            @if(isset($attributes['option']))
                @foreach($attributes['option'] as $key => $value)
                    <option value="{{$key}}">{{ucfirst($value)}}</option>
                @endforeach
            @else
                {{$slot}}
            @endif
        </x-select>
    @elseif($attributes['type']=='textarea')
        <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
        <textarea id="{{$attributes['name']}}"
                  placeholder="{{__($attributes['label'])}}"
                  class="mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm p-3 dark:border-gray-600 dark:bg-dark-eval-1
dark:text-gray-300 dark:focus:ring-offset-dark-eval-1 "
                  wire:model.change="{{$attributes['name']}}"
                  wire:key="{{$attributes['name']}}"
                  name="{{$attributes['name']}}"
                  @if($attributes['disabled']) disabled @endif
                  required>{{$slot}}</textarea>
    @elseif(isset($attributes['bind']) && $attributes['bind'])
        <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
        <x-input class="block mt-1 w-full dark:text-gray-300"
                 type="{{$attributes['type'] ?: 'text'}}"
                 :placeholder="__($attributes['label'])"
                 :name="$attributes['name']"
                 wire:model.change="{{$attributes['name']}}"
                 wire:key="{{$attributes['name']}}"

                 :disabled="@$attributes['disabled'] ?? false"
                 x-bind:value="{{$attributes['bind']}}"
                 x-on:keyup="{{$attributes['onkeyup']}}"
                 required/>
    @elseif($attributes['type'] == 'file')
        @php($existFile = isset($items[str_replace('items.','',$attributes['name'])]) && $items[str_replace('items.','',$attributes['name'])] &&  is_string($items[str_replace('items.','',$attributes['name'])]))
        <diva
            x-data="{ uploading: false, progress: 0,show:false,isExist: {{$existFile ? 'true' : 'false'}} }"
            x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false; progress = 0;"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            <div class="flex gap-2 justify-items-center items-center">
                <div class="flex-grow" x-show="!isExist">
                    <div class="flex">
                        <x-label class="flex-grow" for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
                        <div x-show="uploading">
                            <span class="mt-2" x-text="`${progress}%`"></span>
                        </div>
                    </div>
                    <x-input
                        accept="application/pdf"
                        style="padding-top: 0px;padding-bottom: 0px;"
                        class=" mt-1 block file:text-xs file:font-medium file:border-0 file:py-3 file:bg-red-300 file:text-red-700 file:my-0 py-0 hover:file:cursor-pointer hover:file:bg-red-50 hover:file:text-red-700 w-full text-sm text-red-900 border border-red-300 rounded-lg cursor-pointer bg-red-50 dark:text-red-400 focus:outline-none dark:bg-red-700 dark:border-red-600 dark:placeholder-red-400"
                        type="{{$attributes['type'] ?: 'text'}}"
                        :placeholder="__($attributes['label'])"
                        :name="$attributes['name']"
                        wire:key="{{$attributes['name']}}"
                        wire:model.change="{{$attributes['name']}}"
                        :disabled="@$attributes['disabled'] ?? false"
                        required/>
                </div>
                @if($existFile)
                    <div x-show="!isExist" class="pt-6">
                        <x-secondary-button @click="isExist = !isExist" class="py-3">
                            cancel
                        </x-secondary-button>
                    </div>
                    <div x-show="isExist" class="pt-6 flex-grow flex items-center">
                        <div class="dark:text-gray-200 flex-grow">
                            <x-label class="flex-grow" for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
                            <div>
                                File sudah terupload
                            </div>
                        </div>
                        <x-secondary-button @click="isExist = !isExist" class="py-3">
                            ubah file
                        </x-secondary-button>
                    </div>
                @endif
                {{--            preview--}}
                @if($existFile)
                    @if(env('FILESYSTEM_DISK') == 's3')

                        @php($preview = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK'))
        ->temporaryUrl(
        $items[str_replace('items.','',$attributes['name'])],
        now()->addMinutes(10),
        ['ResponseContentDisposition' => 'inline']
        ))
                    @else
                        @php($preview = asset(\Illuminate\Support\Facades\Storage::url($items[str_replace('items.','',$attributes['name'])])))
                    @endif

                    <div class="flex-shrink pt-6 flex gap-2">
                        <a href="{{ $preview }}"
                           onclick="window.open('{{ $preview }}', 'newwindow', 'width=800,height=600'); return false;"
                        >
                            <x-secondary-button class="py-3">
                            <span class="text-xs text-red-500">
                                <x-heroicon-o-document-download class="h-5 w-5"/>
                            </span>
                            </x-secondary-button>
                        </a>
                        <x-secondary-button @click="show = !show" class="py-3">
                            <div class="text-xs text-red-500">
                                <template x-if="!show">
                                    <x-heroicon-o-eye class="h-5 w-5"/>
                                </template>
                                <template x-if="show">
                                    <x-heroicon-o-eye-off class="h-5 w-5"/>
                                </template>
                            </div>
                        </x-secondary-button>
                    </div>
                @elseif(isset($items[str_replace('items.','',$attributes['name'])]) && $items[str_replace('items.','',$attributes['name'])] && !is_string($items[str_replace('items.','',$attributes['name'])]))
                    @php($preview = $items[str_replace('items.','',$attributes['name'])]->temporaryUrl())
                    <div class="flex-shrink pt-6 flex gap-2">
                        <a href="{{ $preview }}"
                           onclick="window.open('{{ $preview }}', 'newwindow', 'width=800,height=600'); return false;"
                           rel="noopener noreferrer"
                           target="_blank">
                            <x-secondary-button class="py-3">
                            <span class="text-xs text-red-500">
                                <x-heroicon-o-document-download class="h-5 w-5"/>
                            </span>
                            </x-secondary-button>
                        </a>
                        <x-secondary-button @click="show = !show" class="py-3">
                            <div class="text-xs text-red-500">
                                <template x-if="!show">
                                    <x-heroicon-o-eye class="h-5 w-5"/>
                                </template>
                                <template x-if="show">
                                    <x-heroicon-o-eye-off class="h-5 w-5"/>
                                </template>
                            </div>
                        </x-secondary-button>
                    </div>
                @endif
            </div>
            @if(isset($items[str_replace('items.','',$attributes['name'])]) && $items[str_replace('items.','',$attributes['name'])] && is_string($items[str_replace('items.','',$attributes['name'])]))
                @if(env('FILESYSTEM_DISK') == 's3')

                    @php($preview = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK'))
    ->temporaryUrl(
    $items[str_replace('items.','',$attributes['name'])],
    now()->addMinutes(10),
    ['ResponseContentDisposition' => 'inline']
    ))
                @else
                    @php($preview = asset(\Illuminate\Support\Facades\Storage::url($items[str_replace('items.','',$attributes['name'])])))
                @endif
                <div>
                    <template x-if="show">
                        <div class="mt-2">
                            <iframe
                                x-show="show"
                                class="w-full h-dvh"
                                src="{{$preview}}">
                            </iframe>
                        </div>
                    </template>
                </div>
            @elseif(isset($items[str_replace('items.','',$attributes['name'])]) && $items[str_replace('items.','',$attributes['name'])] && !is_string($items[str_replace('items.','',$attributes['name'])]))
                @php($preview = $items[str_replace('items.','',$attributes['name'])]->temporaryUrl(['ResponseContentDisposition' => 'inline']))
                <div>
                    <template x-if="show">
                        <div class="mt-2">
                            <iframe
                                x-show="show"
                                class="w-full h-dvh"
                                src="{{$preview}}">
                            </iframe>
                        </div>
                    </template>
                </div>
            @endif
        </diva>
    @elseif($attributes['type'] == 'currency')
        <div class="w-full" x-data="{
         rawValue: @entangle($attributes['name']),
    formatCurrency() {
        let number = this.rawValue.replace(/\D/g, '');
        this.rawValue = new Intl.NumberFormat('id-ID').format(number);
    },
    removeFormat() {
    if (this.rawValue !== undefined) {
        this.rawValue = this.rawValue.replace(/\D/g, '');
        }
    }
}"
             x-init="formatCurrency()">
            <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
            <x-input
                :wire:model.change="$attributes['name']"
                class="block mt-1 w-full dark:text-gray-300"
                type="text"
                x-model="rawValue"
                @input="rawValue = rawValue.replace(/\D/g, '')"
                @change="formatCurrency()"
                @blur="formatCurrency()"
                @focus="removeFormat()"
                placeholder="Masukan Nominal"
            />
        </div>
    @elseif($attributes['type'] == 'datetime-local')
        <div x-data="{
        datetime: @entangle($attributes['name']),
        formattedDate: ''
    }"
             x-init="formattedDate = datetime ? new Date(datetime).toLocaleDateString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }) : ''"
             class="w-full">

            <x-label for="{{ $attributes['name'] }}" :value="__($attributes['label'])"/>
            <x-input class="block mt-1 w-full"
                     type="datetime-local"
                     x-model="datetime"
                     @change="formattedDate = new Date(datetime).toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
             })"
                     :wire:model.change="$attributes['name']"
                     :disabled="@$attributes['disabled'] ?? false"
                     required/>

            <p class="mt-2 text-sm text-gray-600" x-text="formattedDate"></p>
        </div>
    @elseif($attributes['type'] == 'checkbox')
        <div class="flex gap-2 items-center justify-items-center">
            <x-input class="block mt-1 dark:text-gray-300"
                     type="{{$attributes['type'] ?: 'text'}}"
                     :placeholder="__($attributes['label'])"
                     :name="$attributes['name']"
                     wire:model.change="{{$attributes['name']}}"
                     wire:key="{{$attributes['name']}}"

                     :disabled="@$attributes['disabled'] ?? false"
                     required/>
            <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
        </div>
    @elseif($attributes['type'] == 'map')
        <div
            x-data="locationPicker({
                                lat: @entangle($attributes['name'].'_lat'),
                                lng:  @entangle($attributes['name'].'_lng'),
                                address:  @entangle($attributes['name'].'_alamat'),
                                apiKey: '{{ env('MAPS_API_KEY') }}',
                                el:$el,
                                initialized: false
                            })"
            x-init="initMap()"
        >
            <div x-data="{ show: false,initialized:false }"
            >
                <x-label>{!! $attributes['label'] !!}</x-label>
                <div class="flex gap-2 items-center">
                    <div>
                        <x-button
                            @click="show = !show; initialized = true"
                        >
                            Tambahkan lokasi
                        </x-button>
                    </div>
                    <div>
                        <template x-if="@entangle($attributes['name'].'_lat')">
                            <a
                                x-bind:href="'https://www.google.com/maps/search/?api=1&query=' + $wire.{{$attributes['name']}}_lat + ',' + $wire.{{$attributes['name']}}_lng"
                                target="_blank"
                                class="text-xs text-blue-500 hover:underline">
                                <x-secondary-button>
                                    Lihat di Google Maps
                                </x-secondary-button>
                            </a>
                        </template>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-600 dark:text-gray-100">
                        Lat: <span x-text="$wire.{{$attributes['name']}}_lat"></span>,
                        Lng: <span x-text="$wire.{{$attributes['name']}}_lng"></span><br>
                        Alamat: <span x-text="$wire.{{$attributes['name']}}_alamat"></span>
                    </span>
                </div>
                <div
                    x-show="show"
                    x-transition
                    style="z-index: 9999 !important;"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                >
                    <div

                        @click.outside="show = false"
                        class="bg-white p-6 rounded shadow-lg w-max-full w-[50em]"
                    >

                        <x-message variant="info" label="Cara Penggunaan">
                            <p class="text-sm ">
                                1. Cari lokasi yang diinginkan pada peta.<br>
                                2. Klik pada peta untuk menandai lokasi.<br>
                                2. Geser Titik Map peta untuk menandai lokasi.<br>
                                4. Alamat dan koordinat lokasi akan ditampilkan di bawah peta. <br>
                                5. Jika ada kesalahan pada deskripsi alamat silahkan perbaiki
                            </p>
                        </x-message>
                        <h2 class="text-lg font-bold mb-4">
                            {!! $attributes['label'] !!}
                        </h2>
                        <div
                            wire:ignore
                            class="w-full space-y-2">
                            <x-label>Cari Lokasi</x-label>
                            <gmp-place-autocomplete placeholder="Cari Lokasi" id="autocomplete"
                                                    input-id="autocomplete-input"></gmp-place-autocomplete>
                            <div
                                wire:ignore
                                id="map"
                                zoom="14"
                                map-id="8a037b04795166f9"
                                style="height: 400px"
                            ></div>
                            <x-secondary-button @click="getCurrentLocation()">
                                Lokasi Anda
                            </x-secondary-button>
                            <div class="text-sm text-gray-600">
                                Lat: <span x-text="lat"></span>,
                                Lng: <span x-text="lng"></span><br>
                                <x-label for="address" :value="__('Alamat')"/>
                                <input
                                    type="hidden"
                                    x-model="lat"
                                    x-effect="initialized && $wire.set('{{$attributes['name']}}_lat', lat)"
                                    class="border rounded p-1 w-full"
                                />
                                <input
                                    type="hidden"
                                    x-model="lng"
                                    x-effect="initialized && $wire.set('{{$attributes['name']}}_lng', lng)"
                                    class="border rounded p-1 w-full mt-1"
                                />

                                <textarea
                                    id="address"
                                    x-model="address"
                                    x-effect="initialized && $wire.set('{{$attributes['name']}}_alamat', address)"
                                    class="mt-2 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm p-3 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300 dark:focus:ring-offset-dark-eval-1"
                                    rows="3"
                                    placeholder="Alamat">
                                </textarea>
                            </div>
                        </div>
                        <x-button
                            class="px-4 py-2 bg-red-600 text-white rounded"
                            @click="show = false"
                        >
                            Close
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <x-label for="{{$attributes['name']}}" :value="__($attributes['label'])"/>
        <x-input class="block mt-1 w-full dark:text-gray-300"
                 type="{{$attributes['type'] ?: 'text'}}"
                 :placeholder="__($attributes['label'])"
                 :name="$attributes['name']"
                 wire:model.change="{{$attributes['name']}}"
                 wire:key="{{$attributes['name']}}"

                 :disabled="@$attributes['disabled'] ?? false"
                 required/>
    @endif
    @error($attributes['name'])
    <x-tall-crud-error-message>{{$message}}</x-tall-crud-error-message>
    @enderror
</div>
