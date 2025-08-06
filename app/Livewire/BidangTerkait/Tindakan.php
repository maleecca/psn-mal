<?php

namespace App\Livewire\BidangTerkait;

use App\Models\Laporan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Tindakan extends Component
{
    use WithPagination, WithFileUploads;

    public ?string $q = null;
    public ?Laporan $detail = null;
    public ?int $modal = null;
    public ?array $items = [];

    public function render()
    {
        $datas = Laporan::when($this->q, function ($query) {
            $query->whereBlind('no_laporan', 'no_laporan_index', $this->q)
                ->orWhereBlind('label', 'label_index', $this->q);
        })
            ->where('status', Laporan::DITINDAK_LANJUTI)
            ->latest()
            ->paginate(10);
        return view('livewire.bidang-terkait.tindakan')
            ->with(compact('datas'));
    }

    public function show($id)
    {
        $this->modal = $id;
        $this->detail = Laporan::find($id);
        $this->items['bukti'] = $this->detail->bukti;
    }

    public function updateProgress($status)
    {
        $this->validate([
            'items.dokumen' => 'required',
        ]);
        try {
            $klasifikasi = $this->detail->klasifikasi;
            $path = Laporan::generatePathBukti($klasifikasi);
            \App\Models\Tindakan::create([
                'user_id' => auth()->user()->id,
                'dokumentasi' => isset($this->items['dokumentasi']) ? $this->items['dokumentasi']->store($path, env('FILESYSTEM_DRIVER', 'local')) : null,
                'dokumen' => $this->items['dokumen'],
                'laporan_id' => $this->detail->id,
                'tindakan' => $status,
                'bidang_terkait_id' => $this->detail->currentBidangTerkait()->id,
            ]);
            $this->dispatch('show', [
                'type' => 'success',
                'message' => 'Berhasil memperbarui status laporan',
            ])->to('livewire-toast');
            $this->dispatch('refresh');
            $this->detail = null;
            $this->modal = null;

        } catch (\Throwable $th) {
            $this->dispatch('show', [
                'type' => 'error',
                'message' => 'Gagal memperbarui status laporan',
            ])->to('livewire-toast');
        }

    }
}
