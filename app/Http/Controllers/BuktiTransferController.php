<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BuktiTransferController extends Controller
{
    /**
     * Serve semua file dari storage/app/public secara aman via Laravel.
     * Mengatasi 403 Forbidden yang terjadi pada PHP built-in server di Windows
     * karena Junction (symlink) tidak bisa diakses langsung.
     *
     * Digunakan untuk: bukti_transfer/, bukti_kas/, dan folder storage lainnya.
     */
    public function show(Request $request)
    {
        $path = $request->query('path');

        // Validasi: path tidak boleh kosong atau mengandung path traversal
        if (!$path || Str::contains($path, ['..', "\0"])) {
            abort(404, 'File tidak ditemukan.');
        }

        // Bersihkan leading slash
        $path = ltrim($path, '/');

        // Folder yang diizinkan untuk diakses
        $allowedFolders = ['bukti_transfer', 'bukti_kas'];
        $folder = explode('/', $path)[0] ?? '';
        if (!in_array($folder, $allowedFolders)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Cek apakah file ada di disk public
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        // Ambil file dan serve
        $file     = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
        $size     = Storage::disk('public')->size($path);
        $filename = basename($path);

        return response($file, 200, [
            'Content-Type'        => $mimeType,
            'Content-Length'      => $size,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control'       => 'private, max-age=86400',
        ]);
    }
}
