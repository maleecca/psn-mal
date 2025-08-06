<main class="flex items-center flex-1 sm:justify-center ">
    <div class="h-svh flex-grow hidden md:flex justify-center"
         style="background-image: url('{{ asset('bg.png') }}'); background-size: cover; background-position: center;">
        <div>
        </div>
    </div>
    <div class="w-full px-6 py-4 my-6 overflow-hidden bg-white  sm:max-w-md dark:bg-dark-eval-1 md:mx-7">
        <div class="text-black my-5 border-b py-5">
            <div class="flex justify-center">
                <a href="/" class="flex  gap-3">
                    <x-application-logo class="w-[4em] h-[4em]"/>
                    <span class="text-2xl font-mono whitespace-pre ">LaporinAja</span>
                </a>
            </div>
        </div>
        {{ $slot }}
    </div>
</main>
