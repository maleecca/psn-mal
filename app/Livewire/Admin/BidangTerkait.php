<?php

namespace App\Livewire\Admin;

use App\Models\BidangTerkaitPetugas;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BidangTerkait as BidangTerkaitModel; // Alias untuk menghindari konflik nama

class BidangTerkait extends Component
{
    use WithPagination;

    public $listeners = [
        'refresh' => '$refresh'
    ];
    public ?string $q = null;
    public ?array $items = [];
    public ?bool $modal = null;
    public ?string $edit = null;

    public function query(): Builder
    {
        return BidangTerkaitModel::query() // Gunakan alias di sini
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
        $klasifikasi = BidangTerkaitModel::findOrFail($id); // Gunakan alias di sini
        $this->items = [
            'nama' => $klasifikasi->nama,
            'deskripsi' => $klasifikasi->deskripsi,
        ];
    }

    public function openCreate()
    {
        $this->modal = true;
    }

    public function store()
    {
        $this->validate([
            'items.nama' => 'required',
        ]);
        if ($this->edit) {
            BidangTerkaitModel::findOrFail($this->edit) // Gunakan alias di sini
                ->update([
                    'nama' => $this->items['nama'],
                    'deskripsi' => $this->items['deskripsi'],
                ]);
        } else {
            BidangTerkaitModel::create([ // Gunakan alias di sini
                'nama' => $this->items['nama'],
                'deskripsi' => $this->items['deskripsi'],
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

    public ?array $bidang_terkait_array = [];
    public ?int $bidang_terkait_count = null;
    public ?int $bidang_terkait_id = null;
    public ?array $petugas = [];

    public function openEditPetugas($id)
    {
        $this->bidang_terkait_id = $id;
        $bidang = BidangTerkaitPetugas::where('bidang_terkait_id', $id)
            ->get()
            ->toArray();

        $this->bidang_terkait_array = $bidang ? range(1, count($bidang)) : [];
        $this->bidang_terkait_count = count($this->bidang_terkait_array);
        foreach ($this->bidang_terkait_array as $index) {
            $this->items['petugas_id'][$index] = $bidang[$index - 1]['petugas_id'];
        }

        $this->petugas = \App\Models\User::all()->pluck('name', 'id')->toArray();
    }

    public function deleteBidangPetugas()
    {
        if ($this->bidang_terkait_count) {
            $this->bidang_terkait_array = array_slice($this->bidang_terkait_array, 0, -1);
            $this->bidang_terkait_count = count($this->bidang_terkait_array);
        }
    }

    public function addBidangPetugas()
    {
        $this->bidang_terkait_count = $this->bidang_terkait_count ? $this->bidang_terkait_count + 1 : 1;
        $this->bidang_terkait_array[] = $this->bidang_terkait_count;
    }

    public function storeBidangPetugas()
    {
        $this->validate([
            'items.petugas_id' => 'required',
        ]);
        if ($this->bidang_terkait_id) {
            BidangTerkaitPetugas::where('bidang_terkait_id', $this->bidang_terkait_id)
                ->delete();
            foreach ($this->bidang_terkait_array as $index) {
                BidangTerkaitPetugas::create([
                    'urutan' => $index,
                    'bidang_terkait_id' => $this->bidang_terkait_id,
                    'petugas_id' => $this->items['petugas_id'][$index],
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
        $this->bidang_terkait_id = null;
    }

    // --- METODE BARU UNTUK HAPUS BIDANG TERKAIT ---
    public function delete($id)
    {
        try {
            // Temukan bidang terkait berdasarkan ID
            $bidangTerkait = BidangTerkaitModel::findOrFail($id);

            // Hapus semua petugas yang terkait dengan bidang ini (jika ada)
            // Ini penting untuk menjaga integritas data dan menghindari error foreign key
            BidangTerkaitPetugas::where('bidang_terkait_id', $id)->delete();

            // Hapus bidang terkait itu sendiri
            $bidangTerkait->delete();

            // Kirim notifikasi sukses
            $this->dispatch('show', [
                'type' => 'success',
                'message' => 'Bidang Terkait berhasil dihapus!'
            ])->to('livewire-toast');

            // Refresh komponen untuk memperbarui daftar
            $this->dispatch('refresh');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika ID tidak ditemukan
            $this->dispatch('show', [
                'type' => 'error',
                'message' => 'Bidang Terkait tidak ditemukan.'
            ])->to('livewire-toast');
        } catch (\Throwable $e) {
            // Tangani error umum lainnya
            $this->dispatch('show', [
                'type' => 'error',
                'message' => 'Gagal menghapus Bidang Terkait: ' . $e->getMessage()
            ])->to('livewire-toast');
        }
    }
    // --- AKHIR METODE BARU ---

    public function render()
    {
        $datas = $this->query()->paginate(10);
        return view('livewire.admin.bidang-terkait')
            ->with([
                'datas' => $datas,
            ]);
    }
}