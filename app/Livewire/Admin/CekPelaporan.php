<?php

namespace App\Livewire\Admin;

use App\Models\Laporan;
use Livewire\Component;

class CekPelaporan extends Component
{
    public ?string $search = null;
    public ?Laporan $laporan = null;

    public function searchData()
    {
        $laporan = Laporan::whereBlind('no_laporan','no_laporan_index', $this->search)
            ->first();
        if (!$laporan) {
            $this->dispatch('show', [
                'type' => 'error',
                'message' => 'Laporan tidak ditemukan'
            ])->to('livewire-toast');
            return;
        }
        $this->laporan = $laporan;
        $this->dispatch('show', [
            'type' => 'success',
            'message' => 'Laporan ditemukan'
        ])->to('livewire-toast');
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.admin.cek-pelaporan')
            ->layout('layouts.guest');
    }
}
