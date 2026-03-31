<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuKasController extends Controller
{
    // Menampilkan halaman Pemasukan
    public function pemasukan(Request $request)
    {
        $query = BukuKas::where('jenis_transaksi', 'pemasukan')->with('pencatat');

        if ($request->has('search') && $request->search != '') {
            $query->where('kategori', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        $pemasukan = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $totalPemasukan = BukuKas::where('jenis_transaksi', 'pemasukan')->sum('nominal');

        return view('admin.bukukas.pemasukan', compact('pemasukan', 'totalPemasukan'));
    }

    // Menampilkan halaman Pengeluaran
    public function pengeluaran(Request $request)
    {
        $query = BukuKas::where('jenis_transaksi', 'pengeluaran')->with('pencatat');

        if ($request->has('search') && $request->search != '') {
            $query->where('kategori', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $totalPengeluaran = BukuKas::where('jenis_transaksi', 'pengeluaran')->sum('nominal');

        return view('admin.bukukas.pengeluaran', compact('pengeluaran', 'totalPengeluaran'));
    }

    // Menyimpan data Pemasukan/Pengeluaran manual
    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'required|string|max:50',
            'tanggal'         => 'required|date',
            'nominal'         => 'required|numeric|min:1',
            'keterangan'      => 'nullable|string',
        ]);

        BukuKas::create([
            'jenis_transaksi' => $request->jenis_transaksi,
            'kategori'        => $request->kategori,
            'tanggal'         => $request->tanggal,
            'nominal'         => $request->nominal,
            'keterangan'      => $request->keterangan,
            'user_id'         => Auth::id(), // ID Admin yang mencatat
        ]);

        return redirect()->back()->with('success', 'Transaksi ' . $request->jenis_transaksi . ' berhasil dicatat!');
    }

    // Mengupdate data transaksi
    public function update(Request $request, $id)
    {
        $transaksi = BukuKas::findOrFail($id);

        $request->validate([
            'kategori'   => 'required|string|max:50',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $transaksi->update([
            'kategori'   => $request->kategori,
            'tanggal'    => $request->tanggal,
            'nominal'    => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data transaksi berhasil diperbarui!');
    }

    // Menghapus data transaksi
    public function destroy($id)
    {
        $transaksi = BukuKas::findOrFail($id);
        $jenis = $transaksi->jenis_transaksi;
        $transaksi->delete();

        return redirect()->back()->with('success', 'Data ' . $jenis . ' berhasil dihapus!');
    }
}