<?php

use App\Models\JawabanTemp;
use App\Models\JawabanTempModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

if(!function_exists('temporary_dagulir')) {
    function temporary_dagulir($nId, $id, bool $multiple = false) {
        $temp = JawabanTemp::where('id_jawaban', $id)
            ->where('temporary_dagulir_id', $nId)
            ->orderBy('id', 'desc');

        if($multiple) return $temp->get();
        return $temp->first();
    }
}

if(!function_exists('temporary_select_dagulir')){
    function temporary_select_dagulir(int $id, int $nId){
        $temp = JawabanTempModel::where('id_option', $id)
            ->where('temporary_dagulir_id', $nId)
            ->orderBy('id', 'desc');

        return $temp->first();
    }
}

if(!function_exists('temporary_usulan_dagulir')){
    function temporary_usulan_dagulir(int $id, int $nId){
        $temp = DB::table('temporary_usulan_dan_pendapat')
            ->where('temporary_dagulir_id', $nId)
            ->where('id_aspek', $id)
            ->orderBy('id', 'desc');

        return $temp->first();
    }
}

if (!function_exists('sipde_token')) {
    function sipde_token() {
        $filePath = storage_path('app/response.json');
        $json = json_decode(file_get_contents($filePath), true);
        if ($json['token'] == "") {
            $response = Http::post(config('dagulir.host').'/login', [
                'username' => config('dagulir.username'),
                'password' => config('dagulir.password'),
            ])->json();
            $filePath = storage_path('app/response.json');
            file_put_contents($filePath, json_encode($response));
            $filePath = storage_path('app/response.json');
            $json = json_decode(file_get_contents($filePath), true);
            return [
                'token' => $json['token'],
            ];
        }
        $filePath = storage_path('app/response.json');
        $json = json_decode(file_get_contents($filePath), true);
        $date = Carbon::now()->toDateTimeString();
        if ($date >= $json['exp']) {
            sipde_token();
        }else{
            $filePath = storage_path('app/response.json');
            $json = json_decode(file_get_contents($filePath), true);
            return [
                'token' => $json['token'],
            ];
        }
    }
}

if (!function_exists('list_tipe_pengajuan')) {
    function list_tipe_pengajuan() {
        $data = sipde_token();

        if ($data['token'] != null) {
            $host = config('dagulir.host');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .$data['token'],
            ])
            ->post($host.'/list_tipe_pengajuan.json')->json();
            return $response['data'];
        }else{
            return 'Silahkan login kembali';
        }

    }
}

if (!function_exists('list_jenis_usaha')) {
    function list_jenis_usaha() {
        $data = sipde_token();
        if ($data['token'] != null) {
            $host = config('dagulir.host');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .$data['token'],
            ])
            ->post($host.'/list_jenis_usaha.json')->json();
            return $response['data'];
        }else{
            return 'Silahkan login kembali';
        }

    }
}

if (!function_exists('list_status')) {
    function list_status() {
        $data = sipde_token();
        if ($data['token'] != null) {
            $host = config('dagulir.host');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' .$data['token'],
            ])
            ->post($host.'/list_status.json')->json();
            return json_encode($response);
        }else{
            return 'Silahkan login kembali';
        }
    }
}

if (!function_exists('update_status')) {
    function update_status($kode, $status, $lampiran, $jangka_waktu, $realisasi_waktu ) {
        $data = sipde_token();
        if ($data['token'] != null) {
            $host = config('dagulir.host');
            Http::withHeaders([
                'Authorization' => 'Bearer ' .$data['token'],
            ])->post($host,[
                "kode_pendaftaran" => $kode,
                "status" => $status,
                "lampiran_analisa" => $lampiran,
                "jangka_waktu" => $jangka_waktu,
                "realisasi_dana" => $realisasi_waktu,
            ]);
        }else{
            return 'Silahkan login kembali';
        }
    }
}
if (!function_exists('tambah_pengajuan')) {
    function tambah_pengajuan(
        $nama,
        $nik,
        $tempat_lahir,
        $tanggal_lahir,
        $telp,
        $jenis_usaha,
        $nominal_pengajuan,
        $tujuan_penggunaan,
        $jangka_waktu,
        $ket_agunan,
        $kode_bank_pusat,
        $kode_bank_cabang,
        $kecamatan_sesuai_ktp,
        $kode_kotakab_ktp,
        $alamat_sesuai_ktp,
        $kecamatan_domisili,
        $kode_kotakab_domisili,
        $alamat_domisili,
        $kecamatan_usaha,
        $kode_kotakab_usaha,
        $alamat_usaha,
        $tipe_pengajuan,
        $npwp,
        $jenis_badan_hukum,
        $tempat_berdiri,
        $tanggal_berdiri,
        $email,
        $nama_pj,
    ) {
        $data = sipde_token();
        if ($data['token'] != null) {
            Http::withHeaders([
                'Authorization' => 'Bearer ' .$data['token'],
            ])->post(env('SIPDE_HOST'),[
                "nama" =>  $nama,
                "nik" =>  $nik,
                "tempat_lahir" =>  $tempat_lahir,
                "tanggal_lahir" =>  $tanggal_lahir,
                "telp" =>  $telp,
                "jenis_usaha" =>  $jenis_usaha,
                "nominal_pengajuan" =>  $nominal_pengajuan,
                "tujuan_penggunaan" =>  $tujuan_penggunaan,
                "jangka_waktu" =>  $jangka_waktu,
                "ket_agunan" =>  $ket_agunan,
                "kode_bank_pusat" =>  $kode_bank_pusat,
                "kode_bank_cabang" =>  $kode_bank_cabang,
                "kecamatan_sesuai_ktp" =>  $kecamatan_sesuai_ktp,
                "kode_kotakab_ktp" =>  $kode_kotakab_ktp,
                "alamat_sesuai_ktp" =>  $alamat_sesuai_ktp,
                "kecamatan_domisili" =>  $kecamatan_domisili,
                "kode_kotakab_domisili" =>  $kode_kotakab_domisili,
                "alamat_domisili" =>  $alamat_domisili,
                "kecamatan_usaha" =>  $kecamatan_usaha,
                "kode_kotakab_usaha" =>  $kode_kotakab_usaha,
                "alamat_usaha" =>  $alamat_usaha,
                "tipe_pengajuan" =>  $tipe_pengajuan,
                "npwp" =>  $npwp,
                "jenis_badan_hukum" =>  $jenis_badan_hukum,
                "tempat_berdiri" =>  $tempat_berdiri,
                "tanggal_berdiri" =>  $tanggal_berdiri,
                "email" =>  $email,
                "nama_pj" => $nama_pj,
            ]);
        }else{
            return 'Silahkan login kembali';
        }
    }
}

function formatNumber($param)
{
    return (int)str_replace('.', '', $param);
}

function getDataLevel($data)
{
    $data_level = explode('-', $data);
    return $data_level;
}

function lampiranAnalisa($fileName) {
    $pdf = public_path($fileName);
    $file = "data:@file/pdf;base64,".base64_encode(file_get_contents($pdf));
    // remove white space
    $result = trim($file, "\n\r\t\v\x00");
    return $result;


}

function countAge($tanggal_lahir): int {
    //date in mm/dd/yyyy format; or it can be in other formats as well
    $birthDate = date('m/d/Y', strtotime($tanggal_lahir));
    //explode the date to get month, day and year
    $birthDate = explode("/", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    ? ((date("Y") - $birthDate[2]) - 1)
    : (date("Y") - $birthDate[2]));

    return intval($age);
}
