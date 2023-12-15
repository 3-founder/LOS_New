@extends('layouts.tailwind-template')
@php
    $dataIndex = match ($dataUmum->skema_kredit) {
        'PKPJ' => 1,
        'KKB' => 2,
        'Talangan Umroh' => 1,
        'Prokesra' => 1,
        'Kusuma' => 1,
        'Dagulir' => 1,
        null => 1,
    };

    function getKaryawan($nip){
        $host = env('HCS_HOST');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $host . '/api/v1/karyawan/' . $nip,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        $json = json_decode($response);

        if ($json) {
            if ($json->data)
                return $json->data->nama_karyawan;
        }
    }
@endphp
@section('content')
<section class="">
    <nav class="w-full bg-white p-3  top-[4rem] border sticky">
        <div class="owl-carousel owl-theme tab-wrapper">
            <button data-toggle="tab" data-tab="dagulir" class="btn btn-tab active-tab font-semibold">0% Data Umum</button>
            @foreach ($dataAspek as $item)
                @php
                    $title = str_replace('&', 'dan', strtolower($item->nama));
                    $title = str_replace(' ', '-', strtolower($title));
                @endphp
                <button data-toggle="tab" data-tab="{{$title}}" class="btn btn-tab font-semibold">0% {{$item->nama}}</button>
            @endforeach
            <button data-toggle="tab" data-tab="pendapat-dan-usulan" class="btn btn-tab font-semibold">Pendapat dan Usulan</button>
        </div>
    </nav>
    <div class="p-3">
        <div class="body-pages">
            <form id="pengajuan_kredit" action="{{ route('dagulir.pengajuan.insertkomentar') }}" method="post">
                @csrf
                <div class="mt-3 container mx-auto">
                    <div id="dagulir-tab" class="is-tab-content active">
                        <div class="pb-10 space-y-3">
                            <h2 class="text-4xl font-bold tracking-tighter text-theme-primary">Dagulir</h2>
                            <p class="font-semibold text-gray-400">Review Pengajuan</p>
                        </div>
                        <div class="self-start bg-white w-full border">
                            <div class="p-5 border-b">
                                <h2 class="font-bold text-lg tracking-tighter">
                                    Pengajuan Masuk
                                </h2>
                            </div>
                            <div class="p-5 w-full space-y-5" id="data-umum">

                                <div class="form-group-2">
                                    <div class="input-box">
                                        <label for="">Nama Lengkap</label>
                                        <div class="p-2 bg-white border-b bg-gray-200">
                                            <span class="answer">{{ $dataUmumNasabah->nama ? $dataUmumNasabah->nama : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Email</label>
                                        <div class="p-2 bg-white border-b">
                                            <span class="answer">{{ $dataUmumNasabah->email ? $dataUmumNasabah->email : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Tempat Lahir</label>
                                        <div class="p-2 bg-white border-b">
                                            <span class="answer">{{ $dataUmumNasabah->tempat_lahir ? $dataUmumNasabah->tempat_lahir : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Tanggal Lahir</label>
                                        <div class="p-2 bg-white border-b">
                                            <span class="answer">{{ \Carbon\Carbon::parse($dataUmumNasabah->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Telp</label>
                                        <div class="p-2 bg-white border-b">
                                            <span class="answer">{{ $dataUmumNasabah->telp ? $dataUmumNasabah->telp : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Jenis Usaha</label>
                                        <div class="p-2 bg-white border-b">
                                            @foreach ($jenis_usaha as $key => $value)
                                                {{-- <option value="{{ $key }}">{{ $value }}</option> --}}
                                                <span class="{{ $dataUmumNasabah->jenis_usaha == $key ? 'answer' : '' }}">{{ $dataUmumNasabah->jenis_usaha == $key ? $value : '' }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Foto Nasabah</label>
                                        <div class="p-2 bg-white border-b">
                                            {{-- <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span> --}}
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Status</label>
                                        <div class="p-2 bg-white border-b">
                                            @if ($dataUmumNasabah->status_pernikahan == "1")
                                            <span class="answer" value="1">Belum Menikah</span>
                                            @elseif ($dataUmumNasabah->status_pernikahan == "2")
                                            <span class="answer" value="2">Menikah</span>
                                            @elseif ($dataUmumNasabah->status_pernikahan == "3")
                                            <span class="answer" value="3">Duda</span>
                                            @elseif ($dataUmumNasabah->status_pernikahan == "4")
                                            <span class="answer" value="4">Janda</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">NIK</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Foto KTP Nasabah</label>
                                        <div class="p-2 bg-white border-b">
                                            {{-- <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span> --}}
                                        </div>
                                    </div>
                                    @if ($dataUmumNasabah->status_pernikahan == '2')
                                        <div class="input-box">
                                            <label for="">NIK Pasangan</label>
                                            <div class="p-2 bg-white border-b">
                                                <span>{{ $dataUmumNasabah->nik_pasangan ? $dataUmumNasabah->nik_pasangan : '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <label for="">Foto KTP Pasangan</label>
                                            <div class="p-2 bg-white border-b">
                                                {{-- <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span> --}}
                                            </div>
                                        </div>
                                    @endif
                                    {{-- <div class="input-box">
                                        <label for="">SLIK</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">File Slik</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->jenis_usaha ? $dataUmumNasabah->jenis_usaha : '-' }}</span>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="form-group-3">
                                    <div class="input-box">
                                        <label for="">Kota / Kabupaten KTP</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kab_ktp }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Kecamatan KTP</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kec_ktp }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Desa KTP</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $desa_ktp }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-box">
                                        <label for="">Alamat KTP</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->alamat_ktp}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group-2">
                                    <div class="input-box">
                                        <label for="">Kota / Kabupaten Domisili</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kab_dom }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Kecamatan Domisili </label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kec_dom }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-box">
                                        <label for="">Alamat Domisili</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->alamat_dom }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group-2">
                                    <div class="input-box">
                                        <label for="">Kota / Kabupaten Usaha</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kab_usaha }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Kecamatan Usaha </label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $kec_usaha }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group-2">
                                    <div class="input-box">
                                        <label for="">Plafon</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ 'Rp ' . number_format($dataUmumNasabah->nominal ? $dataUmumNasabah->nominal : 0, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Jangka Waktu</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->jangka_waktu ? $dataUmumNasabah->jangka_waktu : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Tujuan Penggunaan</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->tujuan_penggunaan ? $dataUmumNasabah->tujuan_penggunaan : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="">Jaminan yang Disediakan</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->ket_agunan ? $dataUmumNasabah->ket_agunan : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group-1">
                                    <div class="input-box">
                                        <label for="">Hubungan Bank</label>
                                        <div class="p-2 bg-white border-b">
                                            <span>{{ $dataUmumNasabah->hubungan_bank ? $dataUmumNasabah->hubungan_bank : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-box">
                                    <label for="">Hasil Verifikasi</label>
                                    <div class="p-2 bg-white border-b">
                                        <span>{{ $dataUmumNasabah->hasil_verifikasi ? $dataUmumNasabah->hasil_verifikasi : '-' }}</span>
                                    </div>
                                </div>

                                @if ($dataUmumNasabah->status == 'menikah')
                                    @php
                                        $dataLevelDua = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable')
                                            ->where('level', 2)
                                            ->where('id_parent', $itemKTPSu->id)
                                            ->where('nama', 'Foto KTP Suami')
                                            ->get();
                                    @endphp
                                    @foreach ($dataLevelDua as $item)
                                        @if ($item->opsi_jawaban == 'file')
                                            @php
                                                $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                    ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                    ->where('jawaban_text.id_pengajuan', $dataUmumNasabah->id)
                                                    ->where('jawaban_text.id_jawaban', $item->id)
                                                    ->get();
                                            @endphp
                                            @foreach ($dataDetailJawabanText as $itemTextDua)
                                                @php
                                                    $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text);
                                                @endphp
                                                <div class="form-group-1">
                                                    <label for="">{{ $item->nama }}</label>
                                                    <div class="form-group-1">
                                                        <b>Jawaban:</b>
                                                        <div class="mt-2">
                                                            @if ($file_parts['extension'] == 'pdf')
                                                                <iframe
                                                                    src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    width="100%" height="400px"></iframe>
                                                            @else
                                                                <img src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    alt="" width="400px">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @php
                                        $dataLevelDua = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable')
                                            ->where('level', 2)
                                            ->where('id_parent', $itemKTPSu->id)
                                            ->where('nama', 'Foto KTP Istri')
                                            ->get();
                                    @endphp
                                    @foreach ($dataLevelDua as $item)
                                        @if ($item->opsi_jawaban == 'file')
                                            @php
                                                $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                    ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                    ->where('jawaban_text.id_pengajuan', $dataUmumNasabah->id)
                                                    ->where('jawaban_text.id_jawaban', $item->id)
                                                    ->get();
                                            @endphp
                                            @foreach ($dataDetailJawabanText as $itemTextDua)
                                                @php
                                                    $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text);
                                                @endphp
                                                <div class="form-group-1">
                                                    <label for="">{{ $item->nama }}</label>
                                                    <div class="form-group">
                                                        <b>Jawaban:</b>
                                                        <div class="mt-2">
                                                            @if ($file_parts['extension'] == 'pdf')
                                                                <iframe
                                                                    src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    width="100%" height="400px"></iframe>
                                                            @else
                                                                <img src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    alt="" width="400px">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    @php
                                        $dataLevelDua = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable')
                                            ->where('level', 2)
                                            ->where('id_parent', $itemKTPSu->id)
                                            ->where('nama', 'Foto KTP Nasabah')
                                            ->get();
                                    @endphp
                                    @foreach ($dataLevelDua as $item)
                                        @if ($item->opsi_jawaban == 'file')
                                            @php
                                                $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                    ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                    ->where('jawaban_text.id_pengajuan', $dataUmumNasabah->id)
                                                    ->where('jawaban_text.id_jawaban', $item->id)
                                                    ->get();
                                            @endphp
                                            @foreach ($dataDetailJawabanText as $itemTextDua)
                                                @php
                                                    $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text);
                                                @endphp
                                                <div class="form-group-1">
                                                    <label for="">{{ $item->nama }}</label>
                                                    <div class="form-group-1">
                                                        <b>Jawaban:</b>
                                                        <div class="mt-2">
                                                            @if ($file_parts['extension'] == 'pdf')
                                                                <iframe
                                                                    src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    width="100%" height="400px"></iframe>
                                                            @else
                                                                <img src="{{ asset('..') . '/upload/' . $dataUmumNasabah->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                    alt="" width="400px">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif

                                <div class="form-group-1">
                                    <label for="">{{ $itemSlik?->nama }}</label>
                                    <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                        <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                        <h4 class="font-bold"> {{ $itemSlik?->option }}</h4>
                                    </div>
                                    @php
                                        $komentarSlik = \App\Models\DetailKomentarModel::join('komentar', 'komentar.id', '=', 'detail_komentar.id_komentar')
                                            ->where('id_pengajuan', $dataUmum->id)
                                            ->where('id_item', $itemSlik?->id_item)
                                            ->first();
                                    @endphp
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="hidden" name="id_item[]" value="{{ $itemSlik?->id_item }}">
                                        <input type="hidden" name="id_option[]" value="{{ $itemSlik?->id_jawaban }}">
                                        <div class="">
                                            <input type="text" class="w-full px-4 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400 komentar" name="komentar_penyelia[]"
                                            placeholder="Masukkan Komentar"
                                            value="{{ isset($komentarSlik->komentar) ? $komentarSlik->komentar : '' }}">
                                        </div>
                                        <div class="input-skor">
                                            @php
                                                $skorSlik = $itemSlik?->skor_penyelia ? $itemSlik?->skor_penyelia : $itemSlik?->skor;
                                            @endphp
                                            <input type="number" class="w-full px-4 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400" placeholder="" name="skor_penyelia[]"
                                                onKeyUp="if(this.value>4){this.value='4';}else if(this.value<=0){this.value='1';}"
                                                min="0"
                                                max="4"
                                                {{ $itemSlik?->status_skor == 0 ? 'readonly' : '' }}
                                                value="{{ $skorSlik || $skorSlik > 0 ? $skorSlik : null }}">
                                        </div>
                                    </div>
                                </div>
                                @php
                                    // $key += 1;
                                    // check level 2
                                    $dataLS = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable')
                                        ->where('level', 2)
                                        ->where('id_parent', $itemSP->id)
                                        ->where('nama', 'Laporan SLIK')
                                        ->get();
                                @endphp
                                @foreach ($dataLS as $item)
                                    @if ($item->opsi_jawaban == 'file')
                                        @php
                                            $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                ->where('jawaban_text.id_pengajuan', $dataUmum->id)
                                                ->where('jawaban_text.id_jawaban', $item->id)
                                                ->get();
                                        @endphp
                                        @foreach ($dataDetailJawabanText as $itemTextDua)
                                            @php
                                                $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text);
                                            @endphp
                                            <div class="form-group-1">
                                                <label for="">{{ $item->nama }}</label>
                                            </div>
                                            <div class="form-group-1">
                                                <b>Jawaban:</b>
                                                <div class="mt-2 pl-3">
                                                    @if ($file_parts['extension'] == 'pdf')
                                                        <iframe
                                                            src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                            width="100%" height="800px"></iframe>
                                                    @else
                                                        <img src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                            alt="" width="800px">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach

                                <div class="flex justify-between">
                                    <button type="button"
                                    class="px-5 py-2 border rounded bg-white text-gray-500"
                                    >
                                    Kembali
                                    </button>
                                    <button type="button"
                                    class="px-5 py-2 next-tab border rounded bg-theme-primary text-white"
                                    >
                                    Selanjutnya
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="jumlahData" name="jumlahData" hidden value="{{ $dataUmumNasabah->skema_kredit == 'KKB' ? count($dataAspek) + $dataIndex + 1 : count($dataAspek) + $dataIndex }}">
                    <input type="hidden" id="id_pengajuan" name="id_pengajuan" value="{{ $dataUmum->id }}">
                    @php
                        $dataDetailJawaban = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor')
                            ->where('id_pengajuan', $dataUmum->id)
                            ->get();
                    @endphp
                    @foreach ($dataDetailJawaban as $itemJawabanDetail)
                        <input type="hidden" name="id_jawaban[]" value="{{ $itemJawabanDetail->id }}" id="">
                    @endforeach
                    @foreach ($dataAspek as $key => $value)
                        @php
                            $title_id = str_replace('&', 'dan', strtolower($value->nama));
                            $title_id = str_replace(' ', '-', strtolower($title_id));
                            $title_tab = "$title_id-tab";
                            if ($dataUmumNasabah->skema_kredit == 'KKB')
                                $key += ($dataIndex + 1);
                            else
                                $key += $dataIndex;

                            // check level 2
                            $dataLevelDua = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable', 'is_hide')
                                ->where('level', 2)
                                ->where('id_parent', $value->id)
                                ->get();

                            // check level 4
                            $dataLevelEmpat = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable', 'is_hide')
                                ->where('level', 4)
                                ->where('id_parent', $value->id)
                                ->get();
                            $pendapatStafPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                ->whereNotNull('id_staf')
                                ->where('id_aspek', $value->id)
                                ->first();
                            $pendapatPenyeliaPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                ->whereNotNull('id_penyelia')
                                ->where('id_aspek', $value->id)
                                ->first();
                        @endphp
                        {{-- level level 2 --}}
                        <div id="{{ $title_tab }}" class="is-tab-content">
                            <div class="pb-10 space-y-3">
                                <h2 class="text-4xl font-bold tracking-tighter text-theme-primary">{{$value->nama}}</h2>
                            </div>
                            <div class="self-start bg-white w-full border">
                                <div
                                    class="p-5 w-full space-y-5"
                                    id="{{$title_id}}">
                                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                    @foreach ($dataLevelDua as $item)
                                        @if ($item->opsi_jawaban != 'option')
                                            @if (!$item->is_hide)
                                                @php
                                                    $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                        ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                        ->where('jawaban_text.id_pengajuan', $dataUmum->id)
                                                        ->where('jawaban_text.id_jawaban', $item->id)
                                                        ->get();
                                                @endphp
                                                @foreach ($dataDetailJawabanText as $itemTextDua)
                                                    <div class="">
                                                        <div class="form-group-1 mb-0">
                                                            <h6 class="font-semibold text-sm mb-2" for="">{{ $item->nama }}</h6>
                                                        </div>
                                                        <div class="form-group-1">
                                                            @if ($item->opsi_jawaban == 'file')
                                                                <b>Jawaban: </b>
                                                                @php
                                                                    $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text);
                                                                @endphp
                                                                @if ($file_parts['extension'] == 'pdf')
                                                                    <iframe
                                                                        src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                        width="100%" height="800px"></iframe>
                                                                @else
                                                                    <img src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $item->id . '/' . $itemTextDua->opsi_text }}"
                                                                        alt="" width="800px">
                                                                @endif
                                                                {{-- Rupiah data dua --}}
                                                            @elseif ($item->opsi_jawaban == 'number' && $item->id != 143)
                                                                    <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                        <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                        <h4 class="font-bold">Rp. {{ number_format((int) $itemTextDua->opsi_text, 2, ',', '.') }}</h4>
                                                                    </div>
                                                                @if ($itemTextDua->is_commentable)
                                                                    <input type="hidden" name="id_item[]" value="{{ $item->id }}">
                                                                    @if (Auth::user()->role != 'Pincab')
                                                                        <div class="input-k-bottom">
                                                                            <input type="text" class="form-input komentar"
                                                                                name="komentar_penyelia[]" placeholder="Masukkan Komentar">
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <div class="jawaban-responsive p-2 font-medium">
                                                                    <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                        <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                        @if ($item->id == 79)
                                                                            {{--  NPWP  --}}
                                                                            <h4 class="font-bold npwp">{{$itemTextDua->opsi_text}}</h4>
                                                                        @else
                                                                        <h4 class="font-bold"> {{ str_replace('_', ' ', $itemTextDua->opsi_text) }} {{ $item->opsi_jawaban == 'persen' ? '%' : '' }}</h4>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                                @if ($itemTextDua->is_commentable)
                                                                    @if (Auth::user()->role != 'Pincab')
                                                                        <input type="hidden" name="id_item[]" value="{{ $item->id }}">
                                                                        <div class="input-k-bottom">
                                                                            <input type="text" class="form-input komentar"
                                                                                name="komentar_penyelia[]" placeholder="Masukkan Komentar">
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <input type="text" hidden class="form-input mb-3" placeholder="Masukkan komentar"
                                                        name="komentar_penyelia" value="{{ $itemTextDua->nama }}" disabled>
                                                    <input type="text" hidden class="form-input mb-3" placeholder="Masukkan komentar"
                                                        name="komentar_penyelia" value="{{ $itemTextDua->opsi_text }}" disabled>
                                                    <input type="hidden" name="id_jawaban_text[]" value="{{ $itemTextDua->id }}">
                                                    <input type="hidden" name="id[]" value="{{ $itemTextDua->id_item }}">
                                                @endforeach
                                            @endif
                                        @endif
                                        @php
                                            $dataJawaban = \App\Models\OptionModel::where('option', '!=', '-')
                                                ->where('id_item', $item->id)
                                                ->get();
                                            $dataOption = \App\Models\OptionModel::where('option', '=', '-')
                                                ->where('id_item', $item->id)
                                                ->get();

                                            $getKomentar = \App\Models\DetailKomentarModel::join('komentar', 'komentar.id', '=', 'detail_komentar.id_komentar')
                                                ->where('id_pengajuan', $dataUmum->id)
                                                ->where('id_item', $item->id)
                                                ->where('id_user', Auth::user()->id)
                                                ->first();

                                            // check level 3
                                            $dataLevelTiga = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable', 'is_hide')
                                                ->where('level', 3)
                                                ->where('id_parent', $item->id)
                                                ->get();
                                        @endphp
                                        @foreach ($dataOption as $itemOption)
                                            @if ($itemOption->option == '-')
                                                @if (!$item->is_hide)
                                                    @if ($item->nama != "Ijin Usaha")
                                                        <div class="row col-span-2">
                                                            <div class="form-group-1">
                                                                <h2 class="font-semibold text-lg tracking-tighter ">
                                                                    {{$item->nama}} :
                                                                </h2>
                                                            </div>
                                                            @if ($item->nama == 'Ijin Usaha' && $countIjin == 0)
                                                                <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                    <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                    <h4 class="font-bold">Tidak ada legalitas usaha</h4>
                                                                </div>

                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach

                                        @if (count($dataJawaban) != 0)
                                            @if (!$item->is_hide)
                                                <div class="col-span-2">
                                                    <h2 class="font-semibold text-lg tracking-tighter ">
                                                        {{$item->nama}} :
                                                    </h2>
                                                </div>
                                            @endif
                                            <div class="row">
                                                @foreach ($dataJawaban as $key => $itemJawaban)
                                                    @php
                                                        $dataDetailJawaban = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                            ->where('id_pengajuan', $dataUmum->id)
                                                            ->get();
                                                        $count = count($dataDetailJawaban);
                                                        for ($i = 0; $i < $count; $i++) {
                                                            $data[] = $dataDetailJawaban[$i]['id_jawaban'];
                                                        }
                                                        $getSkorPenyelia = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                            ->where('id_pengajuan', $dataUmum->id)
                                                            ->where('id_jawaban', $itemJawaban->id)
                                                            ->first();
                                                    @endphp
                                                    @if (in_array($itemJawaban->id, $data))
                                                        @if (isset($data))
                                                            <div class="form-group-1">
                                                                @if (!$item->is_hide)
                                                                    @if ($item->nama)
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                    <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                    <h4 class="font-bold">{{ $itemJawaban->option }}</h4>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="input-group input-b-bottom">
                                                                        <input type="hidden" name="id_item[]"
                                                                            value="{{ $item->id }}">
                                                                        <input type="hidden" name="id_option[]"
                                                                            value="{{ $itemJawaban->id }}">
                                                                        @if ($item->is_commentable == 'Ya')
                                                                            <div class="form-group-2">
                                                                                <div class="w-full ">
                                                                                    <label for="">Komentar</label>
                                                                                    <input type="text" class="w-full px-4 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400 komentar"
                                                                                        name="komentar_penyelia[]" placeholder="Masukkan Komentar"
                                                                                        value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                                </div>
                                                                                <div class="w-[15%] m-0">
                                                                                    @php
                                                                                        $skorInput2 = null;
                                                                                        $skorInput2 = $getSkorPenyelia->skor_penyelia ? $getSkorPenyelia->skor_penyelia : $itemJawaban->skor;
                                                                                    @endphp
                                                                                    <label for="">Skor</label>
                                                                                    <input type="number" class="w-full px-3 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400" placeholder=""
                                                                                        name="skor_penyelia[]"
                                                                                        min="0"
                                                                                        max="4"
                                                                                        onKeyUp="if(this.value>4){this.value='4';}else if(this.value<=0){this.value='1';}"
                                                                                        {{ $item->status_skor == 0 ? 'readonly' : '' }}
                                                                                        value="{{ $skorInput2 || $skorInput2 > 0 ? $skorInput2 : null }}">
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <input type="hidden" name="komentar_penyelia[]"
                                                                                value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                            <input type="hidden" name="skor_penyelia[]"
                                                                                value="null">
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <div class="input-group input-b-bottom">
                                                                        <input type="hidden" name="id_item[]"
                                                                            value="{{ $item->id }}">
                                                                        <input type="hidden" name="id_option[]"
                                                                            value="{{ $itemJawaban->id }}">
                                                                        @if ($item->is_commentable == 'Ya')
                                                                            <input type="hidden" name="komentar_penyelia[]" value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                            <div>
                                                                                @php
                                                                                    $skorInput2 = null;
                                                                                    $skorInput2 = $getSkorPenyelia->skor_penyelia ? $getSkorPenyelia->skor_penyelia : $itemJawaban->skor;
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="skor_penyelia[]"
                                                                                    value="{{ $skorInput2 || $skorInput2 > 0 ? $skorInput2 : null }}">
                                                                            </div>
                                                                        @else
                                                                            <input type="hidden" name="komentar_penyelia[]"
                                                                                value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                            <input type="hidden" name="skor_penyelia[]"
                                                                                value="null">
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <input type="text" hidden class="form-input mb-3"
                                                                placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                value="{{ $itemJawaban->option }}" disabled>
                                                            <input type="text" hidden class="form-input mb-3"
                                                                placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                value="{{ $itemJawaban->skor }}" disabled>
                                                            <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        @foreach ($dataLevelTiga as $keyTiga => $itemTiga)
                                            @if (!$itemTiga->is_hide)
                                                @if ($itemTiga->opsi_jawaban != 'option')
                                                    @php
                                                        $dataDetailJawabanText = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                            ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                            ->where('jawaban_text.id_pengajuan', $dataUmum->id)
                                                            ->where('jawaban_text.id_jawaban', $itemTiga->id)
                                                            ->get();

                                                            $jumlahDataDetailJawabanText = $dataDetailJawabanText ? count($dataDetailJawabanText) : 0;
                                                    @endphp
                                                    @foreach ($dataDetailJawabanText as $itemTextTiga)
                                                        @if ($itemTextTiga->nama != 'Ratio Tenor Asuransi')
                                                            <div class="row">
                                                                <div class="form-group-1 mb-0">
                                                                    @if ($itemTiga->opsi_jawaban == 'file')
                                                                        @if ($jumlahDataDetailJawabanText > 1)
                                                                            <h6 class="font-medium text-sm" for="">{{ $itemTextTiga->nama }} {{$loop->iteration}}</h6>
                                                                        @else
                                                                            <h6 class="font-medium text-sm" for="">{{ $itemTextTiga->nama }}</h6>
                                                                        @endif
                                                                    @else
                                                                            <h6 class="font-medium text-sm" for="">{{ $itemTextTiga->nama }}</h6>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-12">
                                                                        @if ($itemTiga->opsi_jawaban == 'file')
                                                                        <b>Jawaban: </b>
                                                                            @php
                                                                                $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmum->id . '/' . $itemTiga->id . '/' . $itemTextTiga->opsi_text);
                                                                            @endphp
                                                                            @if ($file_parts['extension'] == 'pdf')
                                                                                <iframe
                                                                                    style="border: 5px solid #dc3545;"
                                                                                    src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemTiga->id . '/' . $itemTextTiga->opsi_text }}"
                                                                                    width="100%" height="800px"></iframe>
                                                                            @else
                                                                                <img style="border: 5px solid #dc3545;" src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemTiga->id . '/' . $itemTextTiga->opsi_text }}"
                                                                                    alt="" width="800px">
                                                                            @endif
                                                                            {{-- Rupiah data tiga --}}
                                                                        @elseif ($itemTiga->opsi_jawaban == 'number')
                                                                            <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                <h4 class="font-bold">Rp.{{ number_format((int) $itemTextTiga->opsi_text, 2, ',', '.') }}</h4>
                                                                            </div>

                                                                            @if ($item->is_commentable == 'Ya')
                                                                                @if (Auth::user()->role != 'Pincab')
                                                                                    <div class="input-k-bottom">
                                                                                        <input type="hidden" name="id_item[]"
                                                                                            value="{{ $item->id }}">
                                                                                        <input type="text" class="form-input komentar"
                                                                                            name="komentar_penyelia[]"
                                                                                            placeholder="Masukkan Komentar">
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            <div class="jawaban-responsive p-2 font-medium">
                                                                                <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                    <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                    <h4 class="font-bold">{{ $itemTiga->opsi_jawaban == 'persen' ? $itemTextTiga->opsi_text : $itemTextTiga->opsi_text  }}{{ $itemTiga->opsi_jawaban == 'persen' ? '%' : '' }}</h4>
                                                                                </div>

                                                                            </div>
                                                                            @if ($item->is_commentable == 'Ya')
                                                                                @if (Auth::user()->role != 'Pincab')
                                                                                    <div class="input-k-bottom">
                                                                                        <input type="hidden" name="id_item[]"
                                                                                            value="{{ $item->id }}">
                                                                                        <input type="text" class="form-input komentar"
                                                                                            name="komentar_penyelia[]"
                                                                                            placeholder="Masukkan Komentar">
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                </div>
                                                            </div>

                                                            <input type="hidden" class="form-input mb-3" placeholder="Masukkan komentar"
                                                                name="komentar_penyelia" value="{{ $itemTextTiga->nama }}" disabled>
                                                            <input type="hidden" class="form-input mb-3" placeholder="Masukkan komentar"
                                                                name="komentar_penyelia" value="{{ $itemTextTiga->opsi_text }}" disabled>

                                                            <input type="hidden" name="id_jawaban_text[]" value="{{ $itemTextTiga->id }}">
                                                            <input type="hidden" name="id[]" value="{{ $itemTextTiga->id_item }}">
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @php
                                                    // check  jawaban level tiga
                                                    $dataJawabanLevelTiga = \App\Models\OptionModel::where('option', '!=', '-')
                                                        ->where('id_item', $itemTiga->id)
                                                        ->get();
                                                    $dataOptionTiga = \App\Models\OptionModel::where('option', '=', '-')
                                                        ->where('id_item', $itemTiga->id)
                                                        ->get();
                                                    $getKomentar = \App\Models\DetailKomentarModel::join('komentar', 'komentar.id', '=', 'detail_komentar.id_komentar')
                                                        ->where('id_pengajuan', $dataUmum->id)
                                                        ->where('id_item', $itemTiga->id)
                                                        ->first();
                                                    // check level empat
                                                    $dataLevelEmpat = \App\Models\ItemModel::select('id', 'nama', 'opsi_jawaban', 'level', 'id_parent', 'status_skor', 'is_commentable', 'is_hide')
                                                        ->where('level', 4)
                                                        ->where('id_parent', $itemTiga->id)
                                                        ->get();
                                                    // check jawaban kelayakan
                                                    $checkJawabanKelayakan = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor')
                                                        ->where('id_pengajuan', $dataUmum->id)
                                                        ->whereIn('id_jawaban', ['183', '184'])
                                                        ->first();
                                                @endphp

                                                @foreach ($dataOptionTiga as $itemOptionTiga)
                                                    @if (!$itemTiga->is_hide)
                                                        @if ($itemOptionTiga->option == '-')
                                                            @if (isset($checkJawabanKelayakan))
                                                            @else
                                                                <div class="row">
                                                                    <div class="form-group-1">
                                                                        <h5> {{ $itemTiga->nama }}lev3</h5>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach

                                                @if (count($dataJawabanLevelTiga) != 0)
                                                    @if ($itemTiga->nama == 'Ratio Tenor Asuransi Opsi')
                                                    @else
                                                        @if (isset($checkJawabanKelayakan))
                                                            @if ($itemTiga->nama != 'Kelayakan Usaha')
                                                            {{-- @else --}}
                                                                <div class="row col-span-2">
                                                                    <div class="form-group-1">
                                                                        <h6 class="font-medium text-sm" for="">{{ $itemTiga->nama }}</h6>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if ($itemTiga->nama != 'Kelayakan Usaha')
                                                                <div class="row col-span-2">
                                                                    <div class="form-group-1">
                                                                        <h6 class="font-medium text-sm" for="">{{ $itemTiga->nama }}</h6>
                                                                    </div>
                                                                </div>
                                                            @else
                                                            @endif
                                                        @endif
                                                        <div class="row">
                                                            @foreach ($dataJawabanLevelTiga as $key => $itemJawabanLevelTiga)
                                                                {{--  @if (!$itemTiga->is_hide)  --}}
                                                                    @php
                                                                        $dataDetailJawaban = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                                            ->where('id_pengajuan', $dataUmum->id)
                                                                            ->get();

                                                                        $getSkorPenyelia = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                                            ->where('id_pengajuan', $dataUmum->id)
                                                                            ->where('id_jawaban', $itemJawabanLevelTiga->id)
                                                                            ->first();
                                                                        $count = count($dataDetailJawaban);
                                                                        for ($i = 0; $i < $count; $i++) {
                                                                            $data[] = $dataDetailJawaban[$i]['id_jawaban'];
                                                                        }
                                                                    @endphp
                                                                    @if (in_array($itemJawabanLevelTiga->id, $data))
                                                                        @if (isset($data))
                                                                            <div class="form-group-1">
                                                                                @if ($itemTiga->nama != 'Ratio Coverage Opsi')
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                                <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                                <h4 class="font-bold">{{ $itemJawabanLevelTiga->option }}</h4>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <div class="input-group input-b-bottom">
                                                                                    <input type="hidden" name="id_item[]"
                                                                                        value="{{ $itemTiga->id }}">
                                                                                    <input type="hidden" name="id_option[]"
                                                                                        value="{{ $itemJawabanLevelTiga->id }}">
                                                                                    @php
                                                                                        $skorInput3 = null;
                                                                                        $skorInput3 = $getSkorPenyelia?->skor_penyelia ? $getSkorPenyelia?->skor_penyelia : $itemJawabanLevelTiga->skor;
                                                                                    @endphp
                                                                                    @if ($itemTiga->is_commentable == 'Ya')
                                                                                        <div class="grid grid-cols-2 gap-2">
                                                                                            <div class="">
                                                                                                <label for="">Komentar</label>
                                                                                                <input type="text" class="w-full px-4 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400 komentar"
                                                                                                    name="komentar_penyelia[]" placeholder="Masukkan Komentar"
                                                                                                    value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                                            </div>
                                                                                            <div class="input-skor w-[15%]">
                                                                                                <label for="">Skor</label>
                                                                                                <input type="number" class="w-full px-3 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400"
                                                                                                    min="0"
                                                                                                    max="4"
                                                                                                    placeholder="" name="skor_penyelia[]"
                                                                                                    onKeyUp="if(this.value>4){this.value='4';}else if(this.value<=0){this.value='1';}"
                                                                                                    {{ $itemTiga->status_skor == 0 ? 'readonly' : '' }}
                                                                                                    value="{{ $skorInput3 || $skorInput3 > 0 ? $skorInput3 : null }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        <input type="hidden" name="komentar_penyelia[]"
                                                                                            value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                                        <input type="hidden" name="skor_penyelia[]"
                                                                                            value="null">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <input type="text" hidden class="form-input mb-3"
                                                                                placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                                value="{{ $itemJawabanLevelTiga->option }}" disabled>
                                                                            <input type="text" hidden class="form-input mb-3"
                                                                                placeholder="Masukkan komentar" name="skor_penyelia"
                                                                                value="{{ $itemJawabanLevelTiga->skor }}" disabled>
                                                                            <input type="hidden" name="id[]"
                                                                                value="{{ $itemTiga->id }}">
                                                                        @endif
                                                                    @endif
                                                                {{--  @endif  --}}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                                @foreach ($dataLevelEmpat as $keyEmpat => $itemEmpat)
                                                    @if (!$itemEmpat->is_hide)
                                                        @if ($itemEmpat->opsi_jawaban != 'option')
                                                            @php
                                                                $dataDetailJawabanTextEmpat = \App\Models\JawabanTextModel::select('jawaban_text.id', 'jawaban_text.id_pengajuan', 'jawaban_text.id_jawaban', 'jawaban_text.opsi_text', 'item.id as id_item', 'item.nama')
                                                                    ->join('item', 'jawaban_text.id_jawaban', 'item.id')
                                                                    ->where('jawaban_text.id_pengajuan', $dataUmum->id)
                                                                    ->where('jawaban_text.id_jawaban', $itemEmpat->id)
                                                                    ->get();
                                                            @endphp
                                                            @foreach ($dataDetailJawabanTextEmpat as $itemTextEmpat)
                                                                <div class="row">
                                                                    <div class="form-group-1 mb-0">
                                                                        <h6 class="font-medium text-sm" for="">{{ $itemTextEmpat->nama }}</h6>
                                                                        @if ($itemEmpat->opsi_jawaban == 'file')
                                                                                    @if (intval($itemTextEmpat->opsi_text) > 1)
                                                                                        <b>Jawaban:</b>
                                                                                        @php
                                                                                            $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text);
                                                                                        @endphp
                                                                                        @if ($file_parts['extension'] == 'pdf')
                                                                                            <iframe
                                                                                                src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text }}"
                                                                                                width="100%" height="400"></iframe>
                                                                                        @else
                                                                                            <img style="border: 5px solid #c2c7cf"
                                                                                                src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text }}"
                                                                                                alt="" width="400">
                                                                                        @endif
                                                                                    @else
                                                                                        @php
                                                                                            $file_parts = pathinfo(asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text);
                                                                                        @endphp
                                                                                        @if ($file_parts['extension'] == 'pdf')
                                                                                            <iframe
                                                                                                src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text }}"
                                                                                                width="100%" height="500px"></iframe>
                                                                                        @else
                                                                                            <img style="border: 5px solid #c2c7cf"
                                                                                                src="{{ asset('..') . '/upload/' . $dataUmum->id . '/' . $itemEmpat->id . '/' . $itemTextEmpat->opsi_text }}"
                                                                                                alt="" width="500px">
                                                                                        @endif
                                                                                    @endif
                                                                                    {{-- Rupiah data empat --}}
                                                                                @elseif ($itemEmpat->opsi_jawaban == 'number' && $itemEmpat->id != 130)
                                                                                    <div class="jawaban-responsive border-b p-2 font-medium">
                                                                                        <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                            <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                            <h4 class="font-bold"> Rp.{{ number_format((int) $itemTextEmpat->opsi_text, 2, ',', '.') }}</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                    @if ($itemTextEmpat->is_commentable == 'Ya')
                                                                                        @if (Auth::user()->role != 'Pincab')
                                                                                            <div class="input-k-bottom">
                                                                                                <input type="hidden" name="id_item[]"
                                                                                                    value="{{ $item->id }}">
                                                                                                <input type="text"
                                                                                                    class="form-input komentar"
                                                                                                    name="komentar_penyelia[]"
                                                                                                    placeholder="Masukkan Komentar">
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                @else
                                                                                    <div class="jawaban-responsive p-2 font-medium">
                                                                                        <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                            <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                            <h4 class="font-bold">
                                                                                                {{ $itemTextEmpat->opsi_text }}
                                                                                                    @if ($itemEmpat->opsi_jawaban == 'persen')
                                                                                                        %
                                                                                                    @elseif($itemEmpat->id == 130)
                                                                                                        Bulan
                                                                                                    @else
                                                                                                    @endif
                                                                                            </h4>
                                                                                        </div>
                                                                                    </div>
                                                                                    @if ($itemTextEmpat->is_commentable == 'Ya')
                                                                                        @if (Auth::user()->role != 'Pincab')
                                                                                            <div class="input-k-bottom">
                                                                                                <input type="hidden" name="id_item[]"
                                                                                                    value="{{ $item->id }}">
                                                                                                <input type="text"
                                                                                                    class="form-input komentar"
                                                                                                    name="komentar_penyelia[]"
                                                                                                    placeholder="Masukkan Komentar">
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif

                                                                    </div>
                                                                </div>

                                                                <input type="hidden" class="form-input mb-3"
                                                                    placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                    value="{{ $itemTextEmpat->nama }}" disabled>
                                                                <input type="hidden" class="form-input mb-3"
                                                                    placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                    value="{{ $itemTextEmpat->opsi_text }}" disabled>
                                                                <input type="hidden" name="id_jawaban_text[]"
                                                                    value="{{ $itemTextEmpat->id }}">
                                                                <input type="hidden" name="id[]" value="{{ $itemTextEmpat->id_item }}">
                                                            @endforeach
                                                        @endif
                                                        @php
                                                            // check level empat
                                                            $dataJawabanLevelEmpat = \App\Models\OptionModel::where('option', '!=', '-')
                                                                ->where('id_item', $itemEmpat->id)
                                                                ->get();
                                                            $dataOptionEmpat = \App\Models\OptionModel::where('option', '=', '-')
                                                                ->where('id_item', $itemEmpat->id)
                                                                ->get();
                                                            $isJawabanExist = \App\Models\OptionModel::join('jawaban', 'jawaban.id_jawaban', 'option.id')
                                                                ->where('jawaban.id_pengajuan', $dataUmum->id)
                                                                ->where('id_item', $itemEmpat->id)
                                                                ->count();

                                                            $getKomentar = \App\Models\DetailKomentarModel::join('komentar', 'komentar.id', '=', 'detail_komentar.id_komentar')
                                                                ->where('id_pengajuan', $dataUmum->id)
                                                                ->where('id_item', $itemEmpat->id)
                                                                ->first();
                                                            // echo "<pre>";
                                                            // print_r ($dataOptionEmpat);
                                                            // echo "</pre>";
                                                            // ;
                                                        @endphp
                                                        @if ($itemEmpat->opsi_jawaban == 'option' && $isJawabanExist > 0)
                                                            @if ($itemEmpat->nama != "Tidak Memiliki Jaminan Tambahan")
                                                                <div class="row">
                                                                    <div class="form-group-1 mb-0">
                                                                        <label for="">{{ $itemEmpat->nama }}</label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        {{-- Data jawaban Level Empat --}}
                                                        @if (count($dataJawabanLevelEmpat) != 0)
                                                            <div class="row">
                                                                @foreach ($dataJawabanLevelEmpat as $key => $itemJawabanLevelEmpat)
                                                                    @php
                                                                        $dataDetailJawaban = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                                            ->where('id_pengajuan', $dataUmum->id)
                                                                            ->get();
                                                                        $count = count($dataDetailJawaban);
                                                                        for ($i = 0; $i < $count; $i++) {
                                                                            $data[] = $dataDetailJawaban[$i]['id_jawaban'];
                                                                        }
                                                                        $getSkorPenyelia = \App\Models\JawabanPengajuanModel::select('id', 'id_jawaban', 'skor', 'skor_penyelia')
                                                                            ->where('id_pengajuan', $dataUmum->id)
                                                                            ->where('id_jawaban', $itemJawabanLevelEmpat->id)
                                                                            ->first();
                                                                    @endphp
                                                                    @if (in_array($itemJawabanLevelEmpat->id, $data))
                                                                        @if (isset($data))
                                                                            <div class="form-group-1">
                                                                                @if ($itemEmpat->nama != "Tidak Memiliki Jaminan Tambahan")
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="bg-blue-50 border-b border-gray-500 text-gray-700 px-4 py-3 flex items-center" role="alert">
                                                                                                <span class="text-sm font-semibold text-gray-400 mx-3">Jawaban : </span>
                                                                                                <h4 class="font-bold"> {{ $itemJawabanLevelEmpat->option }}</h4>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <div class="input-group input-b-bottom">
                                                                                    <input type="hidden" name="id_item[]"
                                                                                        value="{{ $itemEmpat->id }}">
                                                                                    <input type="hidden" name="id_option[]"
                                                                                        value="{{ $itemJawabanLevelEmpat->id }}">
                                                                                    @if ($itemEmpat->is_commentable == 'Ya')
                                                                                        <div class="grid grid-cols-2 gap-2">
                                                                                            <div class="">
                                                                                                <label for="">Komentar</label>
                                                                                                <input type="text" class="w-full px-4 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400 komentar"
                                                                                                    name="komentar_penyelia[]" placeholder="Masukkan Komentar"
                                                                                                    value="{{ isset($getKomentar->komentar) ? $getKomentar->komentar : '' }}">
                                                                                            </div>
                                                                                            <div class="input-skor w-[15%]">
                                                                                                <label for="">Skor</label>
                                                                                                @php
                                                                                                    $skorInput4 = null;
                                                                                                    $skorInput4 = $getSkorPenyelia?->skor_penyelia ? $getSkorPenyelia?->skor_penyelia : $itemJawabanLevelEmpat->skor;
                                                                                                @endphp
                                                                                                <input type="number" class="w-full px-3 py-2 border-b-2 border-gray-400 outline-none  focus:border-gray-400"
                                                                                                    placeholder="" name="skor_penyelia[]"
                                                                                                    min="0"
                                                                                                    max="4"
                                                                                                    onKeyUp="if(this.value>4){this.value='4';}else if(this.value<=0){this.value='1';}"
                                                                                                    {{ $itemEmpat->status_skor == 0 ? 'readonly' : '' }}
                                                                                                    value="{{ $skorInput4 || $skorInput4 > 0 ? $skorInput4 : null }}">
                                                                                            </div>
                                                                                        </div>

                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" class="form-input mb-3"
                                                                                placeholder="Masukkan komentar" name="komentar_penyelia"
                                                                                value="{{ $itemJawabanLevelEmpat->option }}" disabled>
                                                                            <input type="hidden" name="id[]"
                                                                                value="{{ $itemEmpat->id }}">
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endforeach
                                    </div>
                                    @if (Auth::user()->role == 'PBP')
                                        @php
                                            $getPendapatPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                                ->where('id_aspek', $value->id)
                                                ->where('id_pbp', Auth::user()->id)
                                                ->first();
                                        @endphp
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan {{ $value->nama }}</h4>
                                            <input type="hidden" name="id_aspek[]" value="{{ $value->id }}">
                                            <textarea name="pendapat_per_aspek[]" class="form-input @error('pendapat_per_aspek') is-invalid @enderror"
                                                id="pendapat_per_aspek[]" cols="30" rows="4" placeholder="Pendapat Per Aspek">{{ isset($getPendapatPerAspek->pendapat_per_aspek) ? $getPendapatPerAspek->pendapat_per_aspek : '' }}</textarea>
                                            @error('pendapat_per_aspek')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <hr>
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan Staf Kredit</h4>
                                            <p>{{ $pendapatStafPerAspek->pendapat_per_aspek }}</p>
                                        </div>
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan Penyelia Kredit</h4>
                                            <p>{{ $pendapatPenyeliaPerAspek?->pendapat_per_aspek }}</p>
                                        </div>
                                        @if ($dataUmumNasabah->id_pbo)
                                            <div class="form-group-1">
                                                <h4 class="font-semibold text-base" for="">Pendapat dan Usulan PBO</h4>
                                                <p>{{ $pendapatDanUsulanPBO->komentar_pbo }}</p>
                                            </div>
                                        @endif
                                    @elseif (Auth::user()->role == 'PBO')
                                        @php
                                            $getPendapatPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                                ->where('id_aspek', $value->id)
                                                ->where('id_pbo', Auth::user()->id)
                                                ->first();
                                        @endphp
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan {{ $value->nama }}</h4>
                                            <input type="hidden" name="id_aspek[]" value="{{ $value->id }}">
                                            <textarea name="pendapat_per_aspek[]" class="form-input @error('pendapat_per_aspek') is-invalid @enderror"
                                                id="pendapat_per_aspek[]" cols="30" rows="4" placeholder="Pendapat Per Aspek">{{ isset($getPendapatPerAspek->pendapat_per_aspek) ? $getPendapatPerAspek->pendapat_per_aspek : '' }}</textarea>
                                            @error('pendapat_per_aspek')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <hr>
                                        <hr>
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for=""> Pendapat dan Usulan Staf Kredit</h4>
                                            <p>{{ $pendapatStafPerAspek->pendapat_per_aspek }}</p>
                                        </div>
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for=""> Pendapat dan Usulan Penyelia Kredit</h4>
                                            <p>{{ $pendapatPenyeliaPerAspek->pendapat_per_aspek }}</p>
                                        </div>
                                    @elseif (Auth::user()->role == 'Pincab')
                                        @php
                                            $getPendapatPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                                ->where('id_aspek', $value->id)
                                                ->where('id_pbp', Auth::user()->id)
                                                ->first();
                                        @endphp
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Staf Kredit</label>
                                            <p class="border-b p-2">{{ $pendapatStafPerAspek->pendapat_per_aspek }}</p>
                                        </div>
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Penyelia Kredit</label>
                                            <p class="border-b p-2">{{ $pendapatPenyeliaPerAspek?->pendapat_per_aspek }}</p>
                                        </div>
                                        @if ($dataUmumNasabah->id_pbo)
                                            <div class="form-group-1">
                                                <label for="">Pendapat dan Usulan PBO</label>
                                                <p class="border-b p-2">{{ $pendapatDanUsulanPBO->komentar_pbo }}</p>
                                            </div>
                                        @endif
                                    @else
                                        @php
                                            $getPendapatPerAspek = \App\Models\PendapatPerAspek::where('id_pengajuan', $dataUmum->id)
                                                ->where('id_aspek', $value->id)
                                                ->where('id_penyelia', Auth::user()->id)
                                                ->first();
                                        @endphp
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan {{ $value->nama }}</h4>
                                            <input type="hidden" name="id_aspek[]" value="{{ $value->id }}">
                                            <textarea name="pendapat_per_aspek[]" class="form-input @error('pendapat_per_aspek') is-invalid @enderror"
                                                id="pendapat_per_aspek[]" cols="30" rows="4" placeholder="Pendapat Per Aspek">{{ isset($getPendapatPerAspek->pendapat_per_aspek) ? $getPendapatPerAspek->pendapat_per_aspek : '' }}</textarea>
                                            @error('pendapat_per_aspek')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <hr>
                                        <div class="form-group-1">
                                            <h4 class="font-semibold text-base" for="">Pendapat dan Usulan Staf Kredit</h4>
                                            <p>{{ $pendapatStafPerAspek?->pendapat_per_aspek }}</p>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <button type="button"
                                          class="px-5 py-2 border rounded bg-white text-gray-500"
                                        >
                                          Kembali
                                        </button>
                                        <div>
                                          <button type="button"
                                          class="px-5 prev-tab py-2 border rounded bg-theme-secondary text-white"
                                        >
                                          Sebelumnya
                                        </button>
                                        <button type="button"
                                          class="px-5 next-tab py-2 border rounded bg-theme-primary text-white"
                                        >
                                          Selanjutnya
                                        </button>
                                        <button type="submit" class="px-5 py-2 border rounded bg-green-600 text-white btn-simpan hidden" id="submit">Simpan </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div id="pendapat-dan-usulan-tab" class="is-tab-content">
                        <div class="pb-10 space-y-3">
                            <h2 class="text-4xl font-bold tracking-tighter text-theme-primary">Pendapat dan Usulan</h2>
                        </div>
                        <div class="self-start bg-white w-full border">
                            <div class="p-5 border-b">
                                <h2 class="font-bold text-lg tracking-tighter">
                                    Pendapat dan Usulan
                                </h2>
                            </div>
                            <!-- pendapat-dan-usulan -->
                            <div class="p-5 space-y-5">
                                @if (Auth::user()->role == 'Penyelia Kredit')
                                    <div class="row space-y-5">
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Staf Kredit</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanStaf?->komentar_staff ? $pendapatDanUsulanStaf->komentar_staff : '-' }}
                                            </span>
                                        </div>
                                        <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan Penyelia</label>
                                                <input type="text" name="plafon_usulan_penyelia" class="form-input rupiah" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? null }}">
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan Penyelia</label>
                                                <input type="number" name="jangka_waktu_usulan_penyelia" class="form-input"  value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? null }}">
                                            </div>
                                        </div>
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Penyelia</label>
                                            <textarea name="komentar_penyelia_keseluruhan"
                                                class="form-input @error('komentar_penyelia_keseluruhan') is-invalid @enderror" id="komentar_penyelia_keseluruhan" cols="30"
                                                rows="4" placeholder="Pendapat dan Usulan Penyelia">{{ isset($pendapatDanUsulanPenyelia->komentar_penyelia) ? $pendapatDanUsulanPenyelia->komentar_penyelia : '' }}</textarea>
                                            @error('komentar_penyelia_keseluruhan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif (Auth::user()->role == 'PBO')
                                    <div class="row space-y-5">

                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Staf Kredit</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanStaf?->komentar_staff ? $pendapatDanUsulanStaf->komentar_staff : '-' }}
                                            </span>
                                            <hr>
                                        </div>
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan Penyelia Kredit</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanPenyelia?->komentar_penyelia ? $pendapatDanUsulanPenyelia->komentar_penyelia : '-' }}
                                            </span>
                                        </div>
                                        <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan Penyelia</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? '-' }}" readonly disabled>
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan Penyelia</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? '-' }}" readonly disabled>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan Penyelia</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? '-' }}" readonly disabled>
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan Penyelia</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? '-' }}" readonly disabled>
                                            </div>
                                        </div> --}}
                                        <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan PBO</label>
                                                <input type="text" class="form-input rupiah" name="plafon_usulan_pbo" value="{{ $plafonUsulan?->plafon_usulan_pbo ?? null }}">
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan PBO</label>
                                                <input type="text" class="form-input" name="jangka_waktu_usulan_pbo" value="{{ $plafonUsulan?->jangka_waktu_usulan_pbo ?? null }}">
                                            </div>
                                        </div>
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan PBO</label>
                                            <textarea name="komentar_pbo_keseluruhan"
                                                class="form-input @error('komentar_pbo_keseluruhan') is-invalid @enderror" id="komentar_pbo_keseluruhan" cols="30"
                                                rows="4" placeholder="Pendapat dan Usulan Penyelia Kredit" >{{ isset($pendapatDanUsulanPBO->komentar_pbO) ? $pendapatDanUsulanPBO->komentar_pbO : '' }}</textarea>
                                            @error('komentar_pbo_keseluruhan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif (Auth::user()->role == 'PBP')
                                <div class="space-y-5">
                                    <div class="form-group-1">
                                        <label for="">Pendapat dan Usulan Staf Kredit</label>
                                        <span class="border-b p-2">
                                            {{ $pendapatDanUsulanStaf?->komentar_staff }}
                                        </span>
                                    </div>
                                    <div class="form-group-1">
                                        <label for="">Pendapat dan Usulan Penyelia Kredit</label>
                                        <span class="border-b p-2">
                                            {{ $pendapatDanUsulanPenyelia?->komentar_penyelia }}
                                        </span>
                                    </div>
                                    @if ($dataUmumNasabah->id_pbo)
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan PBO</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanPBO?->komentar_pbo }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                    </div> --}}
                                    <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan PBO</label>
                                            <input type="text" class="form-input rupiah" value="{{ $plafonUsulan?->plafon_usulan_pbo ?? '-' }}" readonly disabled>
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan PBO</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_pbo ?? '-' }}" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan PBP</label>
                                            <input type="text" class="form-input rupiah" name="plafon_usulan_pbp" value="{{ $plafonUsulan?->plafon_usulan_pbp ?? null }}">
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan PBP</label>
                                            <input type="text" class="form-input" name="jangka_waktu_usulan_pbp" value="{{ $plafonUsulan?->jangka_waktu_usulan_pbp ?? null }}">
                                        </div>
                                    </div>
                                    <div class="form-group-1">
                                        <label for="">Pendapat dan Usulan PBP</label>
                                        <textarea name="komentar_pbp_keseluruhan"
                                            class="form-input @error('komentar_pbp_keseluruhan') is-invalid @enderror" id="komentar_pbp_keseluruhan" cols="30"
                                            rows="4" placeholder="Pendapat dan Usulan Penyelia Kredit" >{{ isset($pendapatDanUsulanPBP->komentar_pbp) ? $pendapatDanUsulanPBP->komentar_pbp : '' }}</textarea>
                                        @error('komentar_pbp_keseluruhan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                @else
                                <div class="space-y-5">
                                    <div class="form-group-1">
                                        <label for="">Pendapat dan Usulan Staf Kredit</label>
                                        <span class="border-b p-2">
                                            {{ $pendapatDanUsulanStaf?->komentar_staff }}
                                        </span>
                                        <hr>
                                    </div>
                                    <div class="form-group-1">
                                        <label for="">Pendapat dan Usulan Penyelia Kredit</label>
                                        <span class="border-b p-2">
                                            {{ $pendapatDanUsulanPenyelia?->komentar_penyelia }}
                                        </span>
                                    </div>
                                    @if ($dataUmumNasabah->id_pbo)
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan PBO</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanPBO?->komentar_pbo }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($dataUmumNasabah->id_pbp)
                                        <div class="form-group-1">
                                            <label for="">Pendapat dan Usulan PBP</label>
                                            <span class="border-b p-2">
                                                {{ $pendapatDanUsulanPBP?->komentar_pbp }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan Penyelia</label>
                                            <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_penyelia ?? '-' }}" readonly disabled>
                                        </div>
                                    </div>
                                    @if ($dataUmumNasabah->id_pbo)
                                        <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan PBO</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_pbo ?? '-' }}" readonly disabled>
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan PBO</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_pbo ?? '-' }}" readonly disabled>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($dataUmumNasabah->id_pbp)
                                        <div class="form-group-2">
                                            <div class="input-box">
                                                <label for="">Plafon Usulan PBP</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->plafon_usulan_pbp ?? '-' }}" readonly disabled>
                                            </div>
                                            <div class="input-box">
                                                <label for="">Jangka Waktu Usulan PBP</label>
                                                <input type="text" class="form-input" value="{{ $plafonUsulan?->jangka_waktu_usulan_pbp ?? '-' }}" readonly disabled>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group-2">
                                        <div class="input-box">
                                            <label for="">Plafon Usulan Pincab</label>
                                            <input type="text" name="plafon_usulan_pincab" class="form-input rupiah">
                                        </div>
                                        <div class="input-box">
                                            <label for="">Jangka Waktu Usulan Pincab</label>
                                            <input type="number" name="jangka_waktu_usulan_pincab" class="form-input">
                                        </div>
                                    </div>
                                    <div class="form-group-1 pt-4">
                                        <label for="">Pendapat dan Usulan Pincab</label>
                                        <textarea name="komentar_pincab_keseluruhan"
                                            class="form-input @error('komentar_pincab_keseluruhan') is-invalid @enderror" id="komentar_pincab_keseluruhan" cols="30"
                                            rows="4" placeholder="Pendapat dan Usulan Pincab" >{{ isset($pendapatDanUsulanPincab->komentar_pincab) ? $pendapatDanUsulanPincab->komentar_pincab : '' }}</textarea>
                                        @error('komentar_pincab_keseluruhan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <button class="px-5 py-2 border rounded bg-white text-gray-500">
                                        Kembali
                                    </button>
                                    <div>
                                        <button class="px-5 py-2 border rounded bg-theme-secondary text-white">
                                            Sebelumnya
                                        </button>
                                        <button class="px-5 py-2 border rounded bg-theme-primary text-white" type="submit">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('script-inject')
<script>
    // disabled scrol on input type number
    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    let aspekArr;
    $(window).on('load', function() {
        $("#id_merk").trigger("change");
        aspekArr = <?php echo json_encode($dataAspek); ?>;
    });

    function formatNpwp() {
        var value = $('.npwp').html()
        if (typeof value === 'string') {
            return value.replace(/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/, '$1.$2.$3.$4-$5.$6');
        }
    }

    $(document).ready(function() {
        // Format NPWP
        var npwp = formatNpwp($('.npwp').html())
        $('.npwp').html(npwp)
        const nullValue = []

        function cekValueKosong(formIndex) {
            var skema = $("#skema_kredit").val()
            var form = ".form-wizard[data-index=" + formIndex + "]";
            var inputFile = $(form + " input[type=file]")
            var inputText = $(form + " input[type=text]")
            var inputNumber = $(form + " input[type=number]")
            var select = $(form + " select")
            var textarea = $(form + " textarea")

            $.each(inputFile, function(i, v) {
                if (v.value == '' && !$(this).prop('disabled') && $(this).closest('.filename') == '') {
                    if (form == ".form-wizard[data-index='2']") {
                        var ijin = $(form + " select[name=ijin_usaha]")
                        if (ijin != "tidak_ada_legalitas_usaha") {
                            let val = $(this).attr("id").toString();
                            nullValue.push(val.replaceAll("_", " "))
                        }
                    } else {
                        let val = $(this).attr("id").toString();
                        nullValue.push(val.replaceAll("_", " "))
                    }
                } else if (v.value != '') {
                    let val = $(this).attr("id").toString().replaceAll("_", " ");
                    for (var i = 0; i < nullValue.length; i++) {
                        while (nullValue[i] == val) {
                            nullValue.splice(i, 1)
                            break;
                        }
                    }
                }
            })

            $.each(inputText, function(i, v) {
                if (v.value == '' && !$(this).prop('disabled')) {
                    let val = $(this).attr("id").toString();
                    //console.log(val)
                    nullValue.push(val.replaceAll("_", " "))
                } else if (v.value != '') {
                    let val = $(this).attr("id").toString().replaceAll("_", " ");
                    for (var i = 0; i < nullValue.length; i++) {
                        while (nullValue[i] == val) {
                            nullValue.splice(i, 1)
                            break;
                        }
                    }
                }
            })

            $.each(inputNumber, function(i, v) {
                if (v.value == '' && !$(this).prop('disabled')) {
                    let val = $(this).attr("id").toString();
                    //console.log(val)
                    nullValue.push(val.replaceAll("_", " "))
                } else if (v.value != '') {
                    let val = $(this).attr("id").toString().replaceAll("_", " ");
                    for (var i = 0; i < nullValue.length; i++) {
                        while (nullValue[i] == val) {
                            nullValue.splice(i, 1)
                            break;
                        }
                    }
                }
            })

            $.each(select, function(i, v) {
                if (v.value == '' && !$(this).prop('disabled')) {
                    let val = $(this).attr("id").toString();
                    if (val != "persentase_kebutuhan_kredit_opsi" && val != "ratio_tenor_asuransi_opsi" && val !=
                        "ratio_coverage_opsi") {
                        //console.log(val)
                        nullValue.push(val.replaceAll("_", " "))
                    }
                } else if (v.value != '') {
                    let val = $(this).attr("id").toString().replaceAll("_", " ");
                    for (var i = 0; i < nullValue.length; i++) {
                        while (nullValue[i] == val) {
                            nullValue.splice(i, 1)
                            break;
                        }
                    }
                }
            })

            $.each(textarea, function(i, v) {
                if (v.value == '' && !$(this).prop('disabled')) {
                    let val = $(this).attr("id").toString();
                    //console.log(val)
                    nullValue.push(val.replaceAll("_", " "))
                } else if (v.value != '') {
                    let val = $(this).attr("id").toString().replaceAll("_", " ");
                    for (var i = 0; i < nullValue.length; i++) {
                        while (nullValue[i] == val) {
                            nullValue.splice(i, 1)
                            break;
                        }
                    }
                }
            })
        }

        $(".btn-simpan").on('click', function(e) {
            const role = "{{Auth::user()->role}}"
            if (role == 'Penyelia Kredit') {
                const pendapatPerAspek = $("textarea[id^=pendapat_per_aspek]");
                var msgPendapat = '';
                for (var i = 0; i < pendapatPerAspek.length; i++) {
                    const value = pendapatPerAspek[i].value;
                    if (!value) {
                        const aspek = aspekArr[i].nama
                        msgPendapat += '<li class="text-left">Pendapat pada '+aspek+' harus diisi.</li>';
                    }
                }

                if (msgPendapat != '') {
                    console.log(msgPendapat)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: '<ul>'+msgPendapat+'</ul>'
                    })
                    e.preventDefault()
                }
                else {
                    if ($('#komentar_penyelia_keseluruhan').val() == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Field Pendapat dan usulan harus diisi"
                        })
                        e.preventDefault()
                    }
                    else {
                        if (nullValue.length > 0) {
                            let message = "";
                            $.each(nullValue, (i, v) => {
                                message += v != '' ? v + ", " : ''
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: "Field " + message + " harus diisi terlebih dahulu"
                            })
                            e.preventDefault()
                        }
                    }
                }
            }
            else if (role == 'PBO') {
                if ($('#komentar_pbo_keseluruhan').val() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Field Pendapat dan usulan harus diisi"
                    })
                    e.preventDefault()
                }
                else {
                    if (nullValue.length > 0) {
                        let message = "";
                        $.each(nullValue, (i, v) => {
                            console.log('validasi')
                            console.log(v)
                            console.log('end validasi')
                            message += v != '' ? v + ", " : ''
                        })
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Field " + message + " harus diisi terlebih dahulu"
                        })
                        e.preventDefault()
                    }
                }
            }
            else if (role == 'PBP') {
                if ($('#komentar_pbp_keseluruhan').val() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Field Pendapat dan usulan harus diisi"
                    })
                    e.preventDefault()
                }
                else {
                    if (nullValue.length > 0) {
                        let message = "";
                        $.each(nullValue, (i, v) => {
                            console.log('validasi')
                            console.log(v)
                            console.log('end validasi')
                            message += v != '' ? v + ", " : ''
                        })
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Field " + message + " harus diisi terlebih dahulu"
                        })
                        e.preventDefault()
                    }
                }
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tidak memiliki hak akses untuk melakukan aktivitas ini"
                })
                e.preventDefault()
            }
        })

        if ($('#val_pengembalian').val() == 0) {
            $(".side-wizard li[data-index='0'] a span i").html("0%");
        }else{
            $(".side-wizard li[data-index='0'] a span i").html("100%");
        }

        if ($("#komentar_penyelia_keseluruhan").val() == '') {
            $(".side-wizard li[data-index=9] a span i").html("0%")
        } else {
            $(".side-wizard li[data-index=9] a span i").html("100%")
        }
    })

    @if ($dataUmum->skema_kredit == 'KKB')
        $("#id_merk").change(function() {
            let val = $(this).val();

            $.ajax({
                type: "get",
                url: "{{ route('get-tipe-kendaraan') }}?id_merk=" + val,
                dataType: "json",
                success: (res) => {
                    if (res) {
                        $("#id_tipe").empty();
                        $("#id_tipe").append(`<option>Pilih Tipe</option>`)

                        $.each(res.tipe, function(i, value) {
                            $("#id_tipe").append(`
                            <option value="${value.id}" ${(value.id == {{ $dataPO->id_type }}) ? 'selected' : ''}>${value.tipe}</option>
                        `);
                        })
                    }
                }
            });
        });
    @endif

    var jumlahData = $('#jumlahData').val();
    console.log('Jumlah data : '+jumlahData);
    for (let index = 0; index <= parseInt(jumlahData); index++) {
        // for (let index = 0; index <= parseInt(jumlahData); index++) {
            var selected = index == parseInt(jumlahData) ? ' selected' : ''
            $(".side-wizard li[data-index='" + index + "']").addClass('active' + selected)
            $(".side-wizard li[data-index='" + index + "'] a span i").removeClass('fa fa-ban')
            if ($(".side-wizard li[data-index='" + index + "'] a span i").html() == '' || $(
                    ".side-wizard li[data-index='" + index + "'] a span i").html() == '0%') {
                $(".side-wizard li[data-index='" + index + "'] a span i").html('0%')
            }
        // }

        var form = ".form-wizard[data-index='" + index + "']"

        var input = $(form + " input:disabled");
        var select = $(form + " select")
        var textarea = $(form + " textarea")

        var ttlInput = 0;
        var ttlInputFilled = 0;
        $.each(input, function(i, v) {
            ttlInput++
            if (v.value != '') {
                ttlInputFilled++
            }
        })
        var ttlSelect = 0;
        var ttlSelectFilled = 0;
        $.each(select, function(i, v) {
            ttlSelect++
            if (v.value != '') {
                ttlSelectFilled++
            }
        })

        var ttlTextarea = 0;
        var ttlTextareaFilled = 0;
        $.each(textarea, function(i, v) {
            ttlTextarea++
            if (v.value != '') {
                ttlTextareaFilled++
            }
        })

        if (index == 1) {
            var allInput = ttlInput - 1
            var allInputFilled = ttlInputFilled
        }
        else if (index == 2) {
            if (ttlInput == 6 && ttlInputFilled == 3) {
                var allInput = 6;
                var allInputFilled = 6;
            }
            else {
                var allInput = ttlInput;
                var allInputFilled = ttlInputFilled;
            }
            if (allInput == 0 && allInputFilled == 0) {
                allInput = 1;
                allInputFilled = 1;
            }
        }
        else if (index == 3) {
            var allInput = ttlInput - 3
            var allInputFilled = ttlInputFilled
        }
        else if (index == 4) {
            var allInput = ttlInput
            var allInputFilled = ttlInputFilled
        }
        else if (index == 5) {
            var allInput = ttlInput - 2
            var allInputFilled = ttlInputFilled
        }
        else if (index == 6) {
            var allInput = ttlInput - 1
            var allInputFilled = ttlInputFilled
        }
        else{
            var allInput = ttlInput
            var allInputFilled = ttlInputFilled
        }

        var percentage = parseInt(allInputFilled / allInput * 100);
        percentage = Number.isNaN(percentage) ? 0 : percentage;
        percentage = percentage > 100 ? 100 : percentage;
        percentage = percentage < 0 ? 0 : percentage;

        if (index == 7) {
            if ($("textarea[name=komentar_penyelia_keseluruhan]").val() == '') {
                $(".side-wizard li[data-index='" + index + "'] a span i").html("0%")
            } else {
                $(".side-wizard li[data-index='" + index + "'] a span i").html("100%")
            }
        } else {
            $(".side-wizard li[data-index='" + index + "'] a span i").html(Number.isNaN(percentage) ? 0 + "%" : percentage +
                "%")
        }
        // $(".side-wizard li[data-index='"+index+"'] input.answer").val(allInput);
        // $(".side-wizard li[data-index='"+index+"'] input.answerFilled").val(allInputFilled);
    }

    // $('textarea[name=komentar_penyelia_keseluruhan]').on('change', function() {
    $('#komentar_penyelia_keseluruhan').on('change', function() {
        if ($("textarea[name=komentar_penyelia_keseluruhan]").val() == '') {
            $(".side-wizard li[data-index=9] a span i").html("0%")
        } else {
            $(".side-wizard li[data-index=9] a span i").html("100%")
        }
    })

    function cekBtn() {
        var indexNow = $(".form-wizard.active").data('index')
        var prev = parseInt(indexNow) - 1
        var next = parseInt(indexNow) + 1

        $(".btn-prev").hide()
        $(".btn-simpan").hide()

        $(".progress").prop('disabled', true);
        if ($(".form-wizard[data-index='" + prev + "']").length == 1) {
            $(".btn-prev").show()
        }

        if (parseInt(indexNow) == parseInt(jumlahData)) {
            // $(".btn-next").click(function(e) {
            //     if (parseInt(indexNow) != parseInt(jumlahData)) {
            //         $(".btn-next").show()

            //     }
            $(".btn-simpan").show()
            $(".progress").prop('disabled', false);
            $(".btn-next").hide()
            // });
            // $(".btn-next").show()

        } else {
            $(".btn-next").show()
            $(".btn-simpan").hide()
        }
    }

    function cekWizard(isNext = false) {
        var indexNow = $(".form-wizard.active").data('index')
        // console.log(indexNow);
        if (isNext) {
            $(".side-wizard li").removeClass('active')
        }

        $(".side-wizard li").removeClass('selected')

        for (let index = 0; index <= parseInt(indexNow); index++) {
            var selected = index == parseInt(indexNow) ? ' selected' : ''
            $(".side-wizard li[data-index='" + index + "']").addClass('active' + selected)
            $(".side-wizard li[data-index='" + index + "'] a span i").removeClass('fa fa-ban')
            if ($(".side-wizard li[data-index='" + index + "'] a span i").html() == '' || $(
                    ".side-wizard li[data-index='" + index + "'] a span i").html() == '0%') {
                $(".side-wizard li[data-index='" + index + "'] a span i").html('0%')
            }
        }

    }
    cekBtn()
    cekWizard()

    $(".side-wizard li a").click(function() {
        var dataIndex = $(this).closest('li').data('index')
        if ($(this).closest('li').hasClass('active')) {
            $(".form-wizard").removeClass('active')
            $(".form-wizard[data-index='" + dataIndex + "']").addClass('active')
            cekWizard()
            cekBtn(true)
        }
    })

    function setPercentage(formIndex) {
        var form = ".form-wizard[data-index='" + formIndex + "']"
    }

    $(".btn-next").click(function(e) {
        e.preventDefault();
        var indexNow = $(".form-wizard.active").data('index')
        var next = parseInt(indexNow) + 1
        // \($(".form-wizard[data-index='"+next+"']").length==1);
        // console.log($(".form-wizard[data-index='"+  +"']"));
        if ($(".form-wizard[data-index='" + next + "']").length == 1) {
            // console.log(indexNow);
            $(".form-wizard").removeClass('active')
            $(".form-wizard[data-index='" + next + "']").addClass('active')
            $(".form-wizard[data-index='" + indexNow + "']").attr('data-done', 'true')
        }

        console.log(next);
        cekWizard()
        cekBtn()
        setPercentage(indexNow)
    })
    setPercentage(0)

    $(".btn-prev").click(function(e) {
        event.preventDefault(e);
        var indexNow = $(".form-wizard.active").data('index')
        var prev = parseInt(indexNow) - 1
        if ($(".form-wizard[data-index='" + prev + "']").length == 1) {
            $(".form-wizard").removeClass('active')
            $(".form-wizard[data-index='" + prev + "']").addClass('active')
        }
        cekWizard()
        cekBtn()
        e.preventDefault();
    })

    // Penyelia
    var skorPenyeliaInput1 = document.getElementsByClassName('skorPenyeliaInput1')
    for(var i = 0; i < skorPenyeliaInput1.length; i++) {
        skorPenyeliaInput1[i].addEventListener('wheel', function(event){
            if (event.deltaY < 0)
            {
                var valueInput = parseInt(this.value)+1;
                if (valueInput > 4) {
                    this.value = 4-1
                }
            }
            else if (event.deltaY > 0)
            {
                var valueInput = parseInt(this.value)-1;
                if (valueInput<0) {
                    this.value=0+1
                }
            }
        });
    }

    var skorPenyeliaInput2 = document.getElementsByClassName('skorPenyeliaInput2')
    for(var i = 0; i < skorPenyeliaInput2.length; i++) {
        skorPenyeliaInput2[i].addEventListener('wheel', function(event){
            if (event.deltaY < 0)
            {
                var valueInput = parseInt(this.value)+1;
                if (valueInput > 4) {
                    this.value = 4-1
                }
            }
            else if (event.deltaY > 0)
            {
                var valueInput = parseInt(this.value)-1;
                if (valueInput<0) {
                    this.value=0+1
                }
            }
        });
    }

    var skorPenyeliaInput3 = document.getElementsByClassName('skorPenyeliaInput3')
    for(var i = 0; i < skorPenyeliaInput3.length; i++) {
        skorPenyeliaInput3[i].addEventListener('wheel', function(event){
            if (event.deltaY < 0)
            {
                var valueInput = parseInt(this.value)+1;
                if (valueInput > 4) {
                    this.value = 4-1
                }
            }
            else if (event.deltaY > 0)
            {
                var valueInput = parseInt(this.value)-1;
                if (valueInput<0) {
                    this.value=0+1
                }
            }
        });
    }

    var skorPenyeliaInput4 = document.getElementsByClassName('skorPenyeliaInput4')
    for(var i = 0; i < skorPenyeliaInput4.length; i++) {
        skorPenyeliaInput4[i].addEventListener('wheel', function(event){
            if (event.deltaY < 0)
            {
                var valueInput = parseInt(this.value)+1;
                if (valueInput > 4) {
                    this.value = 4-1
                }
            }
            else if (event.deltaY > 0)
            {
                var valueInput = parseInt(this.value)-1;
                if (valueInput<0) {
                    this.value=0+1
                }
            }
        });
    }


</script>
<script>
    // Start Validation
    @if (count($errors->all()))
        Swal.fire({
            icon: 'error',
            title: 'Error Validation',
            html: `
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            @foreach ($errors->all() as $error)
            <ul>
                <li>{{ $error }}</li>
            </ul>
            @endforeach
        </div>
        `
        });
    @endif

    $(".btn-simpan").on('click', function(e) {
        if ($('#pendapat_usulan').val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Field Pendapat dan usulan harus diisi"
            })
            e.preventDefault()
        }
    })

    // End Validation


    $('#kabupaten').change(function() {
        var kabID = $(this).val();
        if (kabID) {
            $.ajax({
                type: "GET",
                url: "{{ url('') }}/getkecamatan?kabID=" + kabID,
                dataType: 'JSON',
                success: function(res) {
                        console.log(res);
                    if (res) {
                        $("#kecamatan").empty();
                        $("#kecamatan").append('<option>---Pilih Kecamatan---</option>');
                        $.each(res, function(nama, kode) {
                            $('#kecamatan').append(`
                                <option value="${kode}">${nama}</option>
                            `);
                        });

                        $('#kecamatan').trigger('change');
                    } else {
                        $("#kecamatan").empty();
                    }
                }
            });
        } else {
            $("#kecamatan").empty();
        }
    });
    $('#kabupaten_domisili').change(function() {
        var kabID = $(this).val();
        if (kabID) {
            $.ajax({
                type: "GET",
                url: "{{ url('') }}/getkecamatan?kabID=" + kabID,
                dataType: 'JSON',
                success: function(res) {
                        console.log(res);
                    if (res) {
                        $("#kecamatan_domisili").empty();
                        $("#kecamatan_domisili").append('<option>---Pilih Kecamatan---</option>');
                        $.each(res, function(nama, kode) {
                            $('#kecamatan_domisili').append(`
                                <option value="${kode}">${nama}</option>
                            `);
                        });

                        $('#kecamatan_domisili').trigger('change');
                    } else {
                        $("#kecamatan_domisili").empty();
                    }
                }
            });
        } else {
            $("#kecamatan_domisili").empty();
        }
    });
    $('#kabupaten_usaha').change(function() {
        var kabID = $(this).val();
        if (kabID) {
            $.ajax({
                type: "GET",
                url: "{{ url('') }}/getkecamatan?kabID=" + kabID,
                dataType: 'JSON',
                success: function(res) {
                        console.log(res);
                    if (res) {
                        $("#kecamatan_usaha").empty();
                        $("#kecamatan_usaha").append('<option>---Pilih Kecamatan---</option>');
                        $.each(res, function(nama, kode) {
                            $('#kecamatan_usaha').append(`
                                <option value="${kode}">${nama}</option>
                            `);
                        });

                        $('#kecamatan_usaha').trigger('change');
                    } else {
                        $("#kecamatan_usaha").empty();
                    }
                }
            });
        } else {
            $("#kecamatan_usaha").empty();
        }
    });

    $('#status_nasabah').on('change', function(e){
        var status = $(this).val();
        // console.log(status);
        if (status == 2) {
            $('#label-ktp-nasabah').empty();
            $('#label-ktp-nasabah').html('Foto KTP Nasabah');
            $('#nik_pasangan').removeClass('hidden');
            $('#ktp-pasangan').removeClass('hidden');
        } else {
            $('#label-ktp-nasabah').empty();
            $('#label-ktp-nasabah').html('Foto KTP Nasabah');
            $('#nik_pasangan').addClass('hidden');
            $('#ktp-pasangan').addClass('hidden');
        }
    })

    $('#tipe').on('change',function(e) {
        var tipe = $(this).val();
        console.log(tipe);
        if (tipe == '2' || tipe == "0" ) {
            $('#nama_pj').addClass('hidden');
            $('#tempat_berdiri').addClass('hidden');
            $('#tanggal_berdiri').addClass('hidden');
        }else{
            $('#nama_pj').removeClass('hidden');
            $('#tempat_berdiri').removeClass('hidden');
            $('#tanggal_berdiri').removeClass('hidden');
            //badan usaha
            if (tipe == '3') {
                $('#label_pj').html('Nama penanggung jawab');
                $('#input_pj').attr('placeholder', 'Masukkan Nama Penanggung Jawab');
            }
            // perorangan
            else if (tipe == '4') {
                $('#label_pj').html('Nama ketua');
                $('#input_pj').attr('placeholder', 'Masukkan Nama Ketua');
            }
        }
    })

    function validatePhoneNumber(input) {
        var phoneNumber = input.value.replace(/\D/g, '');

        if (phoneNumber.length > 15) {
            phoneNumber = phoneNumber.substring(0, 15);
        }

        input.value = phoneNumber;
    }

    function validateNIK(input) {
        var nikNumber = input.value.replace(/\D/g, '');

        if (nikNumber.length > 16) {
            nikNumber = nikNumber.substring(0, 16);
        }

        input.value = nikNumber;
    }

    $('.rupiah').keyup(function(e) {
        var input = $(this).val()
        $(this).val(formatrupiah(input))
    });
    function formatrupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
        // tab
        $(".tab-wrapper .btn-tab").click(function(e) {
            e.preventDefault();
            var tabId = $(this).data("tab");
            console.log(tabId);

            $(".is-tab-content").removeClass("active");
            $(".tab-wrapper .btn-tab").removeClass(
                "active-tab"
            );
            $(".tab-wrapper .btn-tab").removeClass("active-tab");
            $(".tab-wrapper .btn-tab").removeClass("active-tab");
            $(".tab-wrapper .btn-tab").addClass("disable-tab");

            $(this).addClass("active-tab");
            // $(this).addClass("text-gray-600");

            if (tabId) {
                // $(this).removeClass("text-gray-400");
                // $(this).removeClass("text-gray-400");
                $(this).removeClass("disable-tab");
                $(this).removeClass("disable-tab");
            }

            $("#" + tabId + "-tab").addClass("active");
        });

        $(".next-tab").on("click", function(e) {
            const $activeContent = $(".is-tab-content.active");
            const $nextContent = $activeContent.next();

            console.log($nextContent.length);
            if ($nextContent.length) {
                $activeContent.removeClass("active");
                $nextContent.addClass("active");
            }else{
                $(".next-tab").addClass('hidden');
                $('.btn-simpan').removeClass('hidden')
            }

        });

        $(".prev-tab").on("click", function() {
            const $activeContent = $(".is-tab-content.active");
            const $prevContent = $activeContent.prev();

            if ($prevContent.length) {
                $activeContent.removeClass("active");
                $prevContent.addClass("active");
                $(".next-tab").rem('hidden');
                $('.btn-simpan').addClass('hidden')
            }
        });

        $(".toggle-side").click(function(e) {
            $('.sidenav').toggleClass('hidden')
        })
        $('.owl-carousel').owlCarousel({
            margin: 10,
            autoWidth: true,
            dots: false,
            responsive: {
                0: {
                    items: 3
                },
                600: {
                    items: 5
                },
                1000: {
                    items: 10
                }
            }
        })

        $.each(inputHidden, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if ((!$(this).prop('disabled') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputHidden++;
            }
        })

        $.each(inputFile, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if (!$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden'))
                totalInput++
            var isNull = (v.value == '' || v.value == '0')
            if ((v.value == '' && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputNull++;
            } else if (!isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden')) {
                totalInputFilled++;
            }
        })

        $.each(inputNumber, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if (!$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden'))
                totalInput++
            var isNull = (v.value == '' || v.value == '0') && !$(this).prop('readonly')
            if ((v.value == '' && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputNull++;
            } else if (!isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden')) {
                totalInputFilled++;
            }
        })

        $.each(inputDate, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if (!$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden'))
                totalInput++
            var isNull = (v.value == '' || v.value == '0')
            if ((v.value == '' && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputNull++;
            } else if (!isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden')) {
                totalInputFilled++;
            }
        })

        $.each(select, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if (!$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden'))
                totalInput++
            var isNull = (v.value == '' || v.value == '0')
            if ((isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputNull++;
            } else if (!isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden')) {
                totalInputFilled++;
            }
        })

        $.each(textarea, function(i, v) {
            if ($(this).prop('readonly'))
                totalInputReadOnly++;
            var inputBox = $(this).closest('.input-box');
            if (!$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden'))
                totalInput++
            var isNull = (v.value == '' || v.value == '0')
            if ((v.value == '' && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden')) && !inputBox.hasClass('hidden')) {
                totalInputNull++;
            } else if (!isNull && !$(this).prop('disabled') && !$(this).prop('readonly') && !$(this).hasClass('hidden') && !inputBox.hasClass('hidden')) {
                totalInputFilled++;
            }
        })

        var totalReadHidden = (totalInputHidden + totalInputReadOnly)
        percent = (totalInputFilled / (totalInput - totalInputReadOnly)) * 100

        console.log(`totalInput : ${totalInput}`)
        console.log(`totalInputFilled : ${totalInputFilled}`)
        console.log(`totalInputHidden : ${totalInputHidden}`)
        console.log(`totalInputReadOnly : ${totalInputReadOnly}`)
        console.log(percent)
        return parseInt(percent)
    }

    $(".toggle-side").click(function(e) {
        $('.sidenav').toggleClass('hidden')
    })
    $('.owl-carousel').owlCarousel({
        margin: 10,
        autoWidth: true,
        dots: false,
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 5
            },
            1000: {
                items: 10
            }
        }
    })

    $("#usaha").on("change", function() {
        if ($(this).val() == "tanah") {
            $("#tanah").removeClass("hidden");
            $("#kendaraan").addClass("hidden");
            $("#tanah-dan-bangunan").addClass("hidden");
            $("#form-tanah").removeClass("hidden");
            $("#form-kendaraan").addClass("hidden");
        } else if ($(this).val() == "kendaraan") {
            $("#tanah").addClass("hidden");
            $("#kendaraan").removeClass("hidden");
            $("#tanah-dan-bangunan").addClass("hidden");
            $("#form-tanah").addClass("hidden");
            $("#form-kendaraan").removeClass("hidden");
        } else if ($(this).val() == "tanah-dan-bangunan") {
            $("#tanah").addClass("hidden");
            $("#kendaraan").addClass("hidden");
            $("#tanah-dan-bangunan").removeClass("hidden");
            $("#form-tanah").removeClass("hidden");
            $("#form-kendaraan").addClass("hidden");
        } else {
            $("#form-tanah").addClass("hidden");
            $("#form-kendaraan").addClass("hidden");
            $("#tanah").addClass("hidden");
            $("#tanah-dan-bangunan").addClass("hidden");
            $("#kendaraan").addClass("hidden");
        }
    });
    $("#is-npwp").on("change", function() {
        if ($(this).is(":checked")) {
            $("#npwp").removeClass("hidden");
        } else {
            $("#npwp").addClass("hidden");
        }
    });
    $("#shm-check").on("change", function() {
        if ($(this).is(":checked")) {
            $("#no-shm-input").removeClass("disabled");
            $("#no-shm-input").prop("disabled", false);
        } else {
            $("#no-shm-input").addClass("disabled");
            $("#no-shm-input").prop("disabled", true);
        }
    });
    $("#shgb-check").on("change", function() {
        if ($(this).is(":checked")) {
            $("#no-shgb-input").removeClass("disabled");
            $("#no-shgb-input").prop("disabled", false);
        } else {
            $("#no-shgb-input").addClass("disabled");
            $("#no-shgb-input").prop("disabled", true);
        }
    });
    // petak
    $("#petak-check").on("change", function() {
        if ($(this).is(":checked")) {
            $("#no-petak-input").removeClass("disabled");
            $("#no-petak-input").prop("disabled", false);
        } else {
            $("#no-petak-input").addClass("disabled");
            $("#no-petak-input").prop("disabled", true);
        }
    });
    // ijin usaha
    $("#ijin-usaha").on("change", function() {
        console.log($(this).val());
        if ($(this).val() == "nib") {
            $("#nib").removeClass("hidden");
            $("#label-nib").removeClass("hidden");
            $("#dokumen-nib").removeClass("hidden");
            $("#label-dokumen-nib").removeClass("hidden");
            $("#npwp").removeClass("hidden");
            $("#label-npwp").removeClass("hidden");
            $("#dokumen-npwp").removeClass("hidden");
            $("#label-dokumen-npwp").removeClass("hidden");
            $("#have-npwp").addClass("hidden");
            $("#surat-keterangan-usaha").addClass("hidden");
            $("#label-surat-keterangan-usaha").addClass("hidden");
            $("#dokumen-surat-keterangan-usaha").addClass("hidden");
            $("#label-dokumen-surat-keterangan-usaha").addClass("hidden");
        } else if ($(this).val() == "sku") {
            $("#surat-keterangan-usaha").removeClass("hidden");
            $("#label-surat-keterangan-usaha").removeClass("hidden");
            $("#label-dokumen-surat-keterangan-usaha").removeClass("hidden");
            $("#dokumen-surat-keterangan-usaha").removeClass("hidden");
            $("#have-npwp").removeClass("hidden");
            $("#npwp").addClass("hidden");
            $("#label-npwp").addClass("hidden");
            $("#label-dokumen-npwp").addClass("hidden");
            $("#dokumen-npwp").addClass("hidden");
            $("#label-nib").addClass("hidden");
            $("#nib").addClass("hidden");
            $("#label-dokumen-nib").addClass("hidden");
            $("#dokumen-nib").addClass("hidden");
        } else {
            $("#surat-keterangan-usaha").addClass("hidden");
            $("#label-surat-keterangan-usaha").addClass("hidden");
            $("#label-dokumen-surat-keterangan-usaha").addClass("hidden");
            $("#dokumen-surat-keterangan-usaha").addClass("hidden");
            $("#nib").addClass("hidden");
            $("#label-nib").addClass("hidden");
            $("#dokumen-nib").addClass("hidden");
            $("#label-dokumen-nib").addClass("hidden");
            $("#npwp").addClass("hidden");
            $("#label-npwp").addClass("hidden");
            $("#dokumen-npwp").addClass("hidden");
            $("#label-dokumen-npwp").addClass("hidden");
            $("#have-npwp").addClass("hidden");
            $("#sku").addClass("hidden");
            $("#label-sku").addClass("hidden");
            $("#dokumen-sku").addClass("hidden");
            $("#label-dokumen-sku").addClass("hidden");
        }
    });
    // npwp
    $("#is-npwp").on("change", function() {
        if ($(this).is(":checked")) {
            $("#npwp").removeClass("hidden");
            $("#label-npwp").removeClass("hidden");
            $("#dokumen-npwp").removeClass("hidden");
            $("#label-dokumen-npwp").removeClass("hidden");
        } else {
            $("#npwp").addClass("hidden");
            $("#label-npwp").addClass("hidden");
            $("#dokumen-npwp").addClass("hidden");
            $("#label-dokumen-npwp").addClass("hidden");
        }
    });

    $(document).on('click', '.btn-add', function() {
        const item_id = $(this).data('item-id');
        var item_element = $(`.${item_id}`)
        var iteration = item_element.length
        var input = $(this).closest('.input-box')
        var multiple = input.find('.multiple-action')
        var new_multiple = multiple.html().replaceAll('hidden', '')
        input = input.html().replaceAll(multiple.html(), new_multiple);
        var parent = $(this).closest('.input-box').parent()
        parent.append(`<div class="input-box mb-4">${input}`)
    })

    $(document).on('click', '.btn-minus', function() {
        const item_id = $(this).data('item-id');
        var item_element = $(`#${item_id}`)
        var parent = $(this).closest('.input-box')
        parent.remove()
    })
</script>
@endpush
