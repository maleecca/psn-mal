<?php

namespace App\Livewire\Admin;

use App\Models\Laporan;
use Livewire\Component;

class Dasbor extends Component
{
    public ?array $datas = [];

    public function mount()
    {
        $this->datas = [
            [
                'icon' => 'heroicon-o-document-text',
                'label' => 'Jumlah Laporan Masuk',
                'value' => Laporan::all()->count(),
            ],
            [
                'icon' => 'heroicon-o-document-text',
                'label' => 'Jumlah Laporan Ditindak Lanjuti',
                'value' => Laporan::where('status', Laporan::DITINDAK_LANJUTI)->get()->count(),
            ],
            [
                'icon' => 'heroicon-o-document-text',
                'label' => 'Jumlah Laporan Selesai',
                'value' => Laporan::where('status', Laporan::SELESAI)->get()->count(),
            ]
        ];

    }

    public function render()
    {
        return view('livewire.admin.dasbor');
    }
}
