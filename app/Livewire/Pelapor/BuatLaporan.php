<?php

namespace App\Livewire\Pelapor;

use App\Models\Klasifikasi;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class BuatLaporan extends Component
{
    use WithFileUploads;

    public ?array $items = [];
    public ?Laporan $laporan = null;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        $data = view('livewire.pelapor.buat-laporan');
        if (auth()->check()) {
            $data->layout('layouts.app');
        } else {
            $data->layout('layouts.guest');
        }
        return $data;
    }

    public function store()
    {
        $data = $this->validate([
            'items.label' => 'required',
            'items.email' => 'required|email',
            'items.deskripsi' => 'required',
            'items.bukti' => 'nullable|file|max:1024',
            'items.klasifikasi.0' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $klasifikasi = Klasifikasi::findOrFail(last($this->items['klasifikasi']));
            $path = Laporan::generatePathBukti($klasifikasi);

            $buktiPath = null;
            if (!empty($this->items['bukti'])) {
                $buktiPath = $this->items['bukti']->store($path, env('FILESYSTEM_DRIVER', 'local'));
            }

            $this->laporan = Laporan::create([
                'no_laporan' => Laporan::generateNoLaporan($klasifikasi),
                'bukti' => $buktiPath,
                'label' => $this->items['label'],
                'deskripsi' => $this->items['deskripsi'],
                'klasifikasi_id' => $klasifikasi->id,
                'user_id' => auth()->check() ? auth()->user()->id : null,
                'email' => $this->items['email'],
            ]);

            $this->dispatch('show', [
                'type' => 'success',
                'message' => 'Laporan berhasil dibuat',
            ])->to('livewire-toast');

            DB::commit();
            $this->dispatch('refresh');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('show', [
                'type' => 'error',
                'message' => 'Gagal membuat laporan, silahkan coba lagi <br>' . $e->getMessage() . ':' . $e->getLine(),
            ])->to('livewire-toast');
        }
    }
}
