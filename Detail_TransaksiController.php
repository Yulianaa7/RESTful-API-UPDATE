<?php

namespace App\Http\Controllers;
use App\Detail_Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; //agar bisa menggunakan query

class Detail_TransaksiController extends Controller
{
    public function show(){
        $data = DB::table('detail_transaksi') 
        -> join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
        ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
        ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'produk.nama_produk', 'detail_transaksi.qty', 'detail_transaksi.subtotal')->get();

        return Response()->json($data);
    }

    public function detail($id)
    {
        if(Detail_Transaksi::where('id_detail_transaksi', $id)->exists()) {
        $data = DB::table('detail_transaksi')
           ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
           ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
           ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'produk.nama_produk', 'detail_transaksi.qty', 'detail_transaksi.subtotal')
           ->where('detail_transaksi.id_detail_transaksi', '=', $id)
           ->get();
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
                'id_transaksi' => 'required',
                'id_produk' => 'required',
                'qty' => 'required',
            ]
        );
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $id_produk = $request->id_produk;
        $qty = $request->qty;
        $harga = DB::table('produk')->where('id_produk', $id_produk)->value('harga');
        $subtotal= $harga * $qty;

        $simpan = Detail_Transaksi::create([
        'id_transaksi' => $request->id_transaksi,
        'id_produk' => $id_produk,
        'qty' => $qty,
        'subtotal' => $subtotal
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
            'id_transaksi' => 'required',
            'id_produk' => 'required',
            'qty' => 'required',
        ]);
        
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $id_produk = $request->id_produk;
        $qty = $request->qty;
        $harga = DB::table('produk')->where('id_produk', $id_produk)->value('harga');
        $subtotal= $harga * $qty;
        
        $ubah = Detail_Transaksi::where('id_detail_transaksi', $id)->update([
            'id_transaksi' => $request->id_transaksi,
            'id_produk' => $id_produk,
            'qty' => $qty,
            'subtotal' => $subtotal
        ]);
        if($ubah) {
            return Response()->json(['status' => 1]);
        }else{
            return Response()->json(['status' => 0]);
        }
    }
}
