<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangTerkaitKlasifikasi extends Model
{
    use HasFactory;
    protected $table= 'bidang_terkait_klasifikasi';
    protected $fillable = [
        'urutan',
        'klasifikasi_id',
        'bidang_terkait_id'
    ];
    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id', 'id');
    }
    public function bidang_terkait()
    {
        return $this->belongsTo(BidangTerkait::class, 'bidang_terkait_id', 'id');
    }
}
