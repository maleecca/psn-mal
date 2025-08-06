<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi';
    protected $fillable = [
        'nama',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Klasifikasi::class, 'parent_id');
    }

    public function bidangTindakLanjut()
    {
        $data = $this->bidang_terkait_klasifikasi()->get()->map(function ($item, $index) {
            return '<li> ' . ($index + 1) . '. ' . $item->bidang_terkait->nama . ' </li>';
        })
            ->implode("\n");
        return $data ? '<ol>' . $data . '</ol>' : '-';
    }


    public function bidang_terkait_klasifikasi()
    {
        return $this->hasMany(BidangTerkaitKlasifikasi::class, 'klasifikasi_id', 'id');
    }

}
