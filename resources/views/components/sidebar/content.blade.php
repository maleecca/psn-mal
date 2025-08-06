
<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">
    @php($user = auth()->user())
    @foreach(config('nav') as $key => $item)
        @if(count(@$item['child'] ?: []) <= 0 && $user->can($item['can']))
            <x-sidebar.link :title="ucfirst($key)" href="{{ route($item['route']) }}"
                            :isActive="request()->routeIs($item['route'])">
                <x-slot name="icon">
                    <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                </x-slot>
                {{--                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true"/>--}}
            </x-sidebar.link>
        @elseif($user->can($item['can']))
            <x-sidebar.dropdown :title="ucfirst($key)" :active="Str::startsWith(request()->route()->uri(), 'buttons')">
                <x-slot name="icon">

                    <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                </x-slot>
                @foreach($item['child'] as $k => $child)
                    @if(isset($child['can']) && $user->can($child['can']))
                        <x-sidebar.sublink :title="$k" href="{{ route($child['route']) }}"
                                           :active="request()->routeIs('buttons.text')">
                            <x-slot name="icon">
                                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                            </x-slot>
                        </x-sidebar.sublink>
                    @elseif(!isset($child['can']))
                        <x-sidebar.sublink :title="$k" href="{{ route($child['route']) }}"
                                           :active="request()->routeIs('buttons.text')">
                            <x-slot name="icon">
                                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                            </x-slot>
                        </x-sidebar.sublink>
                    @endif
                @endforeach
            </x-sidebar.dropdown>
        @endif
    @endforeach

    {{-- Examples --}}

    {{-- <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')" /> --}}

    {{-- <x-sidebar.dropdown title="Buttons" :active="Str::startsWith(request()->route()->uri(), 'buttons')">
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Text button" href="{{ route('buttons.text') }}"
            :active="request()->routeIs('buttons.text')" />
        <x-sidebar.sublink title="Icon button" href="{{ route('buttons.icon') }}"
            :active="request()->routeIs('buttons.icon')" />
        <x-sidebar.sublink title="Text with icon" href="{{ route('buttons.text-icon') }}"
            :active="request()->routeIs('buttons.text-icon')" />
    </x-sidebar.dropdown> --}}

</x-perfect-scrollbar>
