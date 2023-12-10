<?php

use App\Models\JawabanTemp;
use App\Models\JawabanTempModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if(!function_exists('temporary')) {
    function temporary($nId, $id, bool $multiple = false) {
        $temp = JawabanTemp::where('id_jawaban', $id)
            ->where('id_temporary_calon_nasabah', $nId)
            ->orderBy('id', 'desc');

        if($multiple) return $temp->get();
        return $temp->first();
    }
}

if(!function_exists('temporary_select')){
    function temporary_select(int $id, int $nId){
        $temp = JawabanTempModel::where('id_option', $id)
            ->where('id_temporary_calon_nasabah', $nId)
            ->orderBy('id', 'desc');

        return $temp->first();
    }
}

if(!function_exists('temporary_usulan')){
    function temporary_usulan(int $id, int $nId){
        $temp = DB::table('temporary_usulan_dan_pendapat')
            ->where('id_temp', $nId)
            ->where('id_aspek', $id)
            ->orderBy('id', 'desc');

        return $temp->first();
    }
}

if (!function_exists('sipde_token')) {
    function sipde_token() {
        $filePath = storage_path('app/response.json');
        $json = json_decode(file_get_contents($filePath), true);
        $date = Carbon::now()->toDateTimeString();
        if ($date >= $json['exp']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token expired silahkan login kembali.',
                    'token' => null,
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mendapatkan token.',
                    'token' => $json['token'],
                ]);
        }
    }
}
