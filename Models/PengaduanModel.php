<?php

/*
 * -----------------------------------------------------------------------------
 * Module Simpel Pengaduan
 * -----------------------------------------------------------------------------
 * @package   Simpel
 * @author    AkarDev.com
 * @copyright Hak Cipta 2026 AkarDev.com
 * @link      https://akar-dev.com
 * -----------------------------------------------------------------------------
 */

namespace Modules\SimpelPengaduan\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SimpelCore\Models\BaseModel;
use Modules\SimpelCore\Models\Modify\PendudukModel;
use Modules\SimpelCore\Traits\ConfigId;
use Modules\SimpelPengaduan\Enums\StatusPengaduanEnum;
use Modules\SimpelPengaduan\Transforms\PengaduanTransform;

class PengaduanModel extends BaseModel
{
    use ConfigId;

    protected $table = 'pengaduan';

    protected $guarded = [];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke balasan anak (thread tanggapan)
     */
    public function child(): HasMany
    {
        return $this->hasMany(self::class, 'id_pengaduan', 'id')->orderBy('created_at', 'asc');
    }

    /**
     * Relasi ke pengaduan induk
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_pengaduan', 'id');
    }

    /**
     * Relasi ke data penduduk berdasarkan NIK (jika ada)
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(PendudukModel::class, 'nik', 'nik');
    }

    /**
     * Scope pengaduan utama (bukan tanggapan/balasan)
     */
    public function scopeUtama($query)
    {
        return $query->whereNull('id_pengaduan');
    }

    /**
     * Scope filter status
     */
    public function scopeFilterStatus($query, $status = null)
    {
        if (! empty($status)) {
            $query->where('status', (int) $status);
        }

        return $query;
    }

    /**
     * Scope pencarian kata kunci
     */
    public function scopeCari($query, ?string $search = null)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
                ->orWhere('nama', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('telepon', 'like', "%{$search}%")
                ->orWhere('isi', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor nomor tiket virtual dalam format LPR-YYYYMM-XXXX.
     */
    public function getNomorTiketAttribute(): string
    {
        $timestamp = $this->created_at
            ? Carbon::parse((string) $this->created_at)->timestamp
            : time();
        $ym = date('Ym', $timestamp);

        return 'LPR-'.$ym.'-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor enum status
     */
    public function getStatusEnumAttribute(): ?StatusPengaduanEnum
    {
        return StatusPengaduanEnum::fromValue($this->status);
    }

    /**
     * Accessor URL foto lampiran pengaduan.
     * Mengembalikan null jika foto kosong atau file tidak ditemukan.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (empty($this->foto)) {
            return null;
        }

        $path = defined('LOKASI_PENGADUAN') ? LOKASI_PENGADUAN : 'desa/upload/pengaduan/';
        $fullFile = FCPATH.$path.$this->foto;

        if (file_exists($fullFile)) {
            return base_url($path.$this->foto);
        }

        return null;
    }

    /**
     * Status label text
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status_enum ? $this->status_enum->label() : 'Tidak Diketahui';
    }

    /**
     * Status badge bootstrap/adminlte class
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status_enum ? $this->status_enum->badgeClass() : 'default';
    }

    /**
     * Memeriksa apakah kata kunci cocok dengan nomor tiket, NIK, atau nomor telepon pengaduan.
     */
    public function isCocokKredensial(?string $kataKunci): bool
    {
        $kataKunci = trim((string) $kataKunci);

        if ($kataKunci === '') {
            return false;
        }

        return $this->nomor_tiket === $kataKunci
            || (! empty($this->nik) && $this->nik === $kataKunci)
            || (! empty($this->telepon) && $this->telepon === $kataKunci);
    }

    /**
     * Transformasi representasi array ringkas untuk balasan/tanggapan pengaduan.
     *
     * @return array<string, mixed>
     */
    public function toBalasanArray(): array
    {
        return PengaduanTransform::transformBalasan($this);
    }

    /**
     * Transformasi representasi detail lengkap pengaduan beserta riwayat balasan untuk pelacakan publik.
     *
     * @return array<string, mixed>
     */
    public function toDetailLacakArray(): array
    {
        return PengaduanTransform::transformDetail($this);
    }

    /**
     * Ekstrak ID numerik dari nomor tiket format LPR-YYYYMM-XXXX atau string numerik biasa.
     * Mengembalikan null jika format tidak dikenali.
     */
    public static function parseIdFromTiket(string $tiket): ?int
    {
        $tiket = trim($tiket);

        if (preg_match('/^LPR-\d{6}-(\d+)$/i', $tiket, $matches)) {
            return (int) $matches[1];
        }

        if (ctype_digit($tiket)) {
            return (int) $tiket;
        }

        return null;
    }

    public static function boot(): void
    {
        parent::boot();
        static::deleting(static function ($model): void {
            if ($model->foto) {
                $lokasi = defined('LOKASI_PENGADUAN') ? LOKASI_PENGADUAN : 'desa/upload/pengaduan/';
                $file = FCPATH.$lokasi.$model->foto;
                if (file_exists($file) && is_file($file)) {
                    @unlink($file);
                }
            }
        });
    }
}

if (! class_exists(Pengaduan::class, false)) {
    class_alias(PengaduanModel::class, Pengaduan::class);
}
