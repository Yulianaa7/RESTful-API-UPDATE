<?php

namespace App\Http\Controllers;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function show(){
        $data = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.id_pelanggan', '=' , 'pelanggan.id_pelanggan')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'pelanggan.id_pelanggan')
            ->get();
        return Response()->json($data);
    }

    public function detail($id){
        if(Transaksi::where('id_transaksi', $id)->exists()){
            $data = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.id_pelanggan', '=' , 'pelanggan.id_pelanggan')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'pelanggan.id_pelanggan')
            ->where('transaksi.id_transaksi', '=', $id)
            ->get();
            return Response()->json($data);
        }else{
            return Response()->json(['message' => 'Tidak Ditemukan']);
        }
    }
    
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),
            [
                'id_pelanggan' => 'required',
            ]
        );
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $simpan = Transaksi::create([
            'id_pelanggan' => $request->id_pelanggan,
            'tgl_transaksi' => date("Y-m-d")
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
        ['id_pelanggan' => 'required']);
        
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        
        $ubah = Order::where('id_transaksi', $id)->update([
            'id_pelanggan' => $request->id_pelanggan,
            'tgl_transaksi' => date("Y-m-d")]);
            
        if($ubah) {
            return Response()->json(['status' => 1]);
        }else{
            return Response()->json(['status' => 0]);
        }
    }
}
