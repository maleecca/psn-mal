<?php

namespace App\Livewire\Admin;

use App\Models\BidangTerkaitKlasifikasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Klasifikasi extends Component
{
    use WithPagination;

    public $listeners = [
        'refresh' => '$refresh'
    ];
    public ?string $q = null;
    public ?array $items = [];
    public ?bool $modal = null;
    public ?string $edit = null;
    public ?int $tindak_lanjut_id = null;
    public ?int $tindak_lanjut_count = null;
    public ?array $bidang_terkait = [];
    public ?Collection $bidang_terkait_klasifikasi;
    public ?array $tindak_lanjut_array;

    public function query(): Builder
    {
        return \App\Models\Klasifikasi::query()
            ->when($this->q, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->q . '%');
                });
            })
            ->latest();
    }

    public function openEdit($id)
    {
        $this->edit = $id;
        $this->modal = $id;
        $klasifikasi = \App\Models\Klasifikasi::findOrFail($id);
        $this->items = [
            'nama' => $klasifikasi->nama,
            'parent_id' => $klasifikasi->parent_id,
        ];
    }

    public function openEditTindakLanjut($id)
    {
        $this->tindak_lanjut_id = $id;
        $bidang = BidangTerkaitKlasifikasi::where('klasifikasi_id', $id)
            ->orderBy('urutan', 'asc')
            ->get()
            ->toArray();

        $this->tindak_lanjut_array = $bidang ? range(1, count($bidang)) : [];
        $this->tindak_lanjut_count = count($this->tindak_lanjut_array);
        foreach ($this->tindak_lanjut_array as $index) {
            $this->items['bidang_terkait_id'][$index] = $bidang[$index - 1]['bidang_terkait_id'];
        }

        $this->bidang_terkait = \App\Models\BidangTerkait::pluck('nama', 'id')->toArray();
    }

    public function deleteTindakLanjuti()
    {
        if ($this->tindak_lanjut_count) {
            $this->tindak_lanjut_array = array_slice($this->tindak_lanjut_array, 0, -1);
            $this->tindak_lanjut_count = count($this->tindak_lanjut_array);
        }
    }

    public function storeTindakLanjut()
    {
        $this->validate([
            'items.bidang_terkait_id' => 'required',
        ]);
        if ($this->tindak_lanjut_id) {
            BidangTerkaitKlasifikasi::where('klasifikasi_id', $this->tindak_lanjut_id)
                ->delete();
            foreach ($this->tindak_lanjut_array as $index) {
                BidangTerkaitKlasifikasi::create([
                    'urutan' => $index,
                    'klasifikasi_id' => $this->tindak_lanjut_id,
                    'bidang_terkait_id' => $this->items['bidang_terkait_id'][$index],
                ]);
            }
        }
        $this->dispatch('show', [
            'type' => 'success',
            'message' => 'Berhasil diupdate'
        ])->to('livewire-toast');
        $this->dispatch('refresh');
        $this->modal = null;
        $this->edit = null;
        $this->tindak_lanjut_id = null;
    }

    public function addTindakLanjuti()
    {
        $this->tindak_lanjut_count = $this->tindak_lanjut_count ? $this->tindak_lanjut_count + 1 : 1;
        $this->tindak_lanjut_array[] = $this->tindak_lanjut_count;

    }

    public function openCreate()
    {
        $this->modal = true;
    }

    public
    function store()
    {
        $this->validate([
            'items.nama' => 'required',
        ]);
        if ($this->edit) {
            \App\Models\Klasifikasi::findOrFail($this->edit)
                ->update([
                    'nama' => $this->items['nama'],
                    'parent_id' => isset($this->items['parent_id']) && $this->items['parent_id'] ? $this->items['parent_id'] : null,
                ]);
        } else {
            \App\Models\Klasifikasi::create([
                'nama' => $this->items['nama'],
                'parent_id' => isset($this->items['parent_id']) && $this->items['parent_id'] ? $this->items['parent_id'] : null,
            ]);
        }
        $this->dispatch('show', [
            'type' => 'success',
            'message' => 'Berhasil diupdate'
        ])->to('livewire-toast');
        $this->dispatch('refresh');
        $this->modal = null;
        $this->edit = null;
    }

    public function delete($id)
    {

        \App\Models\Klasifikasi::findOrFail($id)
            ->delete();
        $this->dispatch('confirm', [
            'message' => 'Berhasil dihapus',
            'type' => 'succesc',
        ])->to('livewire-toast');

    }

    public
    function render()
    {
        $datas = $this->query()->paginate(10);
        return view('livewire.admin.klasifikasi')
            ->with([
                'datas' => $datas,
            ]);
    }
}
