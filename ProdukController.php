<?php

namespace App\Http\Controllers;
use App\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function show(){
        return Produk::all();
    }

    public function detail($id){
        if(Produk::where('id_produk', $id)->exists()) {
            $data = DB::table('produk')->where('produk.id_produk', '=', $id)->get();
            return Response()->json($data);
            }
        else {
            return Response()->json(['message' => 'Tidak ditemukan' ]);
        }
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),
            [
                'nama_produk' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'foto_produk' => 'required',
            ]
        );
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $simpan = Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto_produk' => $request->foto_produk,
        ]);
        if($simpan)
        {
            return Response()->json(['status' => 1]);
        }
        else
        {
            return Response()->json(['status' => 0]);
        }
    }

    public function update($id, Request $request){
        $validator=Validator::make($request->all(),
        [   
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'foto_produk' => 'required',
        ]);
        
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        
        $ubah = Produk::where('id_produk', $id)->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto_produk' => $request->foto_produk,
        ]);
        if($ubah) {
            return Response()->json(['status' => 1]);
        }else{
            return Response()->json(['status' => 0]);
        }
    }
}
