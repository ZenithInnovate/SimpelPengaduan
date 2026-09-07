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

namespace Modules\SimpelPengaduan\Http\Requests;

use Modules\SimpelCore\Http\Requests\FormRequest;

class KirimPengaduanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'nik' => 'nullable|string|max:20',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'telepon.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'judul.required' => 'Judul laporan / pengaduan wajib diisi.',
            'isi.required' => 'Rincian pengaduan wajib diisi.',
            'foto.image' => 'Berkas lampiran harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
            'foto.max' => 'Ukuran gambar maksimal 3 MB.',
        ];
    }
}
