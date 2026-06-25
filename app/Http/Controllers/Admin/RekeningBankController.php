<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekeningBank;
use Illuminate\Http\Request;

class RekeningBankController extends Controller
{
    public function index()
    {
        $rekening = RekeningBank::latest()->get();
        return view('admin.rekening.index', compact('rekening'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        RekeningBank::create($request->all());
        return redirect()->back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        $rek = RekeningBank::findOrFail($id);
        $rek->update($request->all());
        return redirect()->back()->with('success', 'Rekening berhasil diupdate.');
    }

    public function destroy($id)
    {
        RekeningBank::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Rekening berhasil dihapus.');
    }
}
