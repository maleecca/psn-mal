<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangTerkaitPetugas extends Model
{
    use HasFactory;
    protected $table = 'bidang_terkait_petugas';
    protected $fillable = [
        'bidang_terkait_id',
        'petugas_id',
    ];
    public function bidang_terkiat()
    {
        return $this->belongsTo(BidangTerkait::class, 'bidang_terkait_id', 'id');
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }
}
