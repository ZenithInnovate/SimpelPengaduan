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

// BACKEND — semua di bawah prefix "simpel/pengaduan"
Route::group('simpel/pengaduan', ['namespace' => 'SimpelPengaduan/BackEnd'], static function (): void {
    Route::get('/', 'PengaduanController@index');
    Route::get('datatables', 'PengaduanController@datatables');
    Route::get('detail/{id}', 'PengaduanController@detail');
    Route::post('tanggapi/{id}', 'PengaduanController@tanggapi');
    Route::post('status/{id}', 'PengaduanController@ubahStatus');
    Route::match(['GET', 'POST'], 'delete', 'PengaduanController@destroy');
});

// FRONTEND — portal publik pelaporan & pelacakan tiket pengaduan
Route::group('layanan-pengaduan', ['namespace' => 'SimpelPengaduan/FrontEnd'], static function (): void {
    Route::get('/', 'PengaduanWargaController@index');
    Route::post('kirim', 'PengaduanWargaController@store');
    Route::get('lacak', 'PengaduanWargaController@lacak');
    Route::post('tanggapi/{id}', 'PengaduanWargaController@balas');
});
