<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangTerkait extends Model
{
    use HasFactory;
    protected $table = 'bidang_terkait';
    protected $fillable = [
        'nama',
        'deskripsi',
    ];
    public function petugas()
    {
        return $this->hasMany(BidangTerkaitPetugas::class, 'bidang_terkait_id', 'id');
    }
    public function petugasList()
    {
        $data = $this->petugas()->get()->map(function ($item, $index) {
            return '<li> ' . ($index + 1) . '. ' . $item->petugas->name . ' </li>';
        })
            ->implode("\n");
        return $data ? '<ol>' . $data . '</ol>' : '-';

    }
}
