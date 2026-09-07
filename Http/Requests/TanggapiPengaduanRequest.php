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

class TanggapiPengaduanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'isi' => 'required|string',
            'status' => 'nullable|integer|in:1,2,3',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];
    }

    public function messages(): array
    {
        return [
            'isi.required' => 'Tanggapan / balasan tidak boleh kosong.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'foto.image' => 'Lampiran tanggapan harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
            'foto.max' => 'Ukuran gambar maksimal 3 MB.',
        ];
    }
}
