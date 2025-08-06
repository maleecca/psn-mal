<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\Constants;
use ParagonIE\CipherSweet\EncryptedRow;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;

class Laporan extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use UsesCipherSweet;

    const DITINDAK_LANJUTI = 1;
    const SELESAI = 2;
    protected $table = 'laporan';
    protected $fillable = [
        'no_laporan',
        'bukti',
        'label',
        'deskripsi',
        'klasifikasi_id',
        'user_id',
        'email',
        'status',
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow->addField('no_laporan')
            ->addBlindIndex('no_laporan', new BlindIndex('no_laporan_index', []))
            ->addField('label')
            ->addBlindIndex('label', new BlindIndex('label_index', []))
            ->addField('deskripsi');
    }

    public static function generateNoLaporan($klasifikasi)
    {
        $counter = optional(self::latest('id')->first())->no_laporan
            ? (int)explode('/', self::latest('id')->first()->no_laporan)[1] + 1
            : 1;
        $prefix = collect(explode(' ', $klasifikasi->nama))
            ->map(fn($word) => strtoupper($word[0]))
            ->join('');
        return sprintf('%s/%04d', strtoupper($prefix), $counter);
    }

    public static function generatePathBukti(Klasifikasi $klasifikasi)
    {
        return 'laporan/' . collect(explode(' ', $klasifikasi->nama))
                ->map(fn($word) => strtoupper($word))
                ->join('') . '/' . date('Ymd');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id', 'id');
    }

    const STATUS = [
        0 => 'Menunggu Tindakan',
        1 => 'Di Tindaklanjuti',
        2 => 'Selesai',
        3 => 'Selesai',
    ];

    public function status()
    {
        return self::STATUS[$this->status] ?: 'Menunggu Tindakan';
    }

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class, 'laporan_id', 'id');
    }

    public function urutan()
    {
        return $this->klasifikasi->bidang_terkait_klasifikasi;
    }

    public function currentBidangTerkait()
    {
        foreach ($this->urutan() as $item) {
            $exist = $this->tindakan()
                ->where('status', '1')
                ->where('bidang_terkait_id', $item->bidang_terkait_id)
                ->get();
            if ($exist->count() < 1) {
                return $item->bidang_terkait;
            }
        }
        return null;

    }

    public function progress()
    {
        $data = $this->urutan()->map(function ($item, $index) {
            $status = $this->tindakan()
                ->where('status', '1')
                ->where('bidang_terkait_id', $item->bidang_terkait_id)
                ->get();
            return [
                'bidang_terkait' => $item->bidang_terkait->nama,
                'tindakan' => $this->tindakan()->where('bidang_terkait_id', $item->bidang_terkait_id)->get(),
                'status' => $status->count() > 0 ? 1 : 0,
            ];
        });
        return $data;
    }
}
