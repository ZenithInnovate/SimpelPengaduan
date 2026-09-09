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

use App\Models\Penduduk;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SimpelCore\Models\BaseModel;
use Modules\SimpelPengaduan\Enums\StatusPengaduanEnum;

class Pengaduan extends BaseModel
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
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
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
     * Accessor nomor tiket virtual
     */
    public function getNomorTiketAttribute(): string
    {
        return simpel_pengaduan_nomor_tiket($this->id, $this->created_at);
    }

    /**
     * Accessor enum status
     */
    public function getStatusEnumAttribute(): ?StatusPengaduanEnum
    {
        return StatusPengaduanEnum::fromValue($this->status);
    }

    /**
     * Accessor URL lampiran foto
     */
    public function getFotoUrlAttribute(): ?string
    {
        return simpel_pengaduan_foto_url($this->foto);
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
