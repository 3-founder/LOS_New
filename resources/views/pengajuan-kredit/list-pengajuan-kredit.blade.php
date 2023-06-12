@extends('layouts.template')
@section('content')
@include('components.notification')
<div class="table-responsive">
    <table class="table table-hover table-custom">
        <thead>
            <tr class="table-primary">
                <th class="text-center">#</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Nasabah</th>
                <th>Posisi</th>
                <th>Durasi</th>
                <th>Skor</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_pengajuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>
                        @if ($item->posisi == 'Proses Input Data')
                            Staff
                        @elseif ($item->posisi == 'Review Penyelia')
                            Penyelia
                        @elseif ($item->posisi == 'PBO')
                            PBO
                        @elseif ($item->posisi == 'PBP')
                            PBP
                        @else
                            Pincab
                        @endif
                    </td>
                    <td>
                        @if ($item->posisi == 'Proses Input Data')
                            @php
                                $rentangStaff = \App\Models\PengajuanModel::find($item->id);
                                $awal = date_create(date(now()));
                                $akhir = date_create($rentangStaff->tanggal);
                                $interval = $awal->diff($akhir);
                                $result_rentang = $interval->format('%a');
                            @endphp
                            {{-- {{ $rentangPenyelia }} --}}
                            {{-- {{ $result_rentang.' hari' }} --}}
                            @if ($result_rentang != 0)
                                @if ($result_rentang == 1 || $result_rentang == 2 || $result_rentang == 3)
                                    <font class="text-success">{{ $result_rentang.' hari' }}</font>
                                @elseif ($result_rentang == 4 || $result_rentang == 5 || $result_rentang == 6)
                                    <font class="text-warning">{{ $result_rentang.' hari' }}</font>
                                @else
                                    <font class="text-danger">{{ $result_rentang.' hari' }}</font>
                                @endif
                            @else
                                {{ '-' }}
                            @endif
                        @elseif ($item->posisi == 'Review Penyelia')
                            @php
                                $rentangPenyelia = \App\Models\PengajuanModel::find($item->id);
                                $awal = date_create(date(now()));
                                $akhir = date_create($rentangPenyelia->tanggal_review_penyelia);
                                $interval = $awal->diff($akhir);
                                $result_rentang = $interval->format('%a');
                            @endphp
                            @if ($item->tanggal_review_penyelia != null)
                                @if ($result_rentang != 0)
                                    @if ($result_rentang == 1 || $result_rentang == 2 || $result_rentang == 3)
                                        <font class="text-success">{{ $result_rentang.' hari' }}</font>
                                    @elseif ($result_rentang == 4 || $result_rentang == 5 || $result_rentang == 6)
                                        <font class="text-warning">{{ $result_rentang.' hari' }}</font>
                                    @else
                                        <font class="text-danger">{{ $result_rentang.' hari' }}</font>
                                    @endif
                                @else
                                    {{ '-' }}
                                @endif
                            @endif
                        @elseif ($item->posisi == 'PBP')
                            @php
                                $rentangpbp = \App\Models\PengajuanModel::find($item->id);
                                $awal = date_create(date(now()));
                                $akhir = date_create($rentangpbp->tanggal_review_pbp);
                                $interval = $awal->diff($akhir);
                                $result_rentang = $interval->format('%a');
                            @endphp
                            @if ($item->tanggal_review_pbp != null)
                                @if ($result_rentang != 0)
                                    @if ($result_rentang == 1 || $result_rentang == 2 || $result_rentang == 3)
                                        <font class="text-success">{{ $result_rentang.' hari' }}</font>
                                    @elseif ($result_rentang == 4 || $result_rentang == 5 || $result_rentang == 6)
                                        <font class="text-warning">{{ $result_rentang.' hari' }}</font>
                                    @else
                                        <font class="text-danger">{{ $result_rentang.' hari' }}</font>
                                    @endif
                                @else
                                    {{ '-' }}
                                @endif
                            @endif
                        @else
                            @php
                                $rentangPincab = \App\Models\PengajuanModel::find($item->id);
                                $awal = date_create(date(now()));
                                $akhir = date_create($rentangPincab->tanggal_review_pincab);
                                $interval = $awal->diff($akhir);
                                $result_rentang_pincab = $interval->format('%a');
                            @endphp
                            @if ($item->tanggal_review_pincab != null)
                                @if ($result_rentang_pincab != 0)
                                    @if ($result_rentang_pincab == 1 || $result_rentang_pincab == 2 || $result_rentang_pincab == 3)
                                        <font class="text-success">{{ $result_rentang_pincab.' hari' }}</font>
                                    @elseif ($result_rentang_pincab == 4 || $result_rentang_pincab == 5 || $result_rentang_pincab == 6)
                                        <font class="text-warning">{{ $result_rentang_pincab.' hari' }}</font>
                                    @else
                                        <font class="text-danger">{{ $result_rentang_pincab.' hari' }}</font>
                                    @endif
                                @else
                                    {{ '-' }}
                                @endif
                            @endif
                        @endif

                    </td>
                    <td>
                        @if ($item->average_by_penyelia != null)
                            @if ($item->status == 'hijau')
                                <font class="text-success">
                                    {{ $item->average_by_penyelia }}
                                </font>
                            @elseif ($item->status == 'kuning')
                                <font class="text-warning">
                                    {{ $item->average_by_penyelia }}
                                </font>
                            @elseif ($item->status == 'merah')
                                <font class="text-danger">
                                    {{ $item->average_by_penyelia }}
                                </font>
                            @else
                                <font class="text-secondary">
                                    {{ $item->average_by_penyelia }}
                                </font>
                            @endif
                        @else
                            @if ($item->status_by_sistem == 'hijau')
                                <font class="text-success">
                                    {{ $item->average_by_sistem }}
                                </font>
                            @elseif ($item->status_by_sistem == 'kuning')
                                <font class="text-warning">
                                    {{ $item->average_by_sistem }}
                                </font>
                            @elseif ($item->status_by_sistem == 'merah')
                                <font class="text-danger">
                                    {{ $item->average_by_sistem }}
                                </font>
                            @else
                                <font class="text-secondary">
                                    {{ $item->average_by_sistem }}
                                </font>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($item->posisi == 'Selesai')
                            <font class="text-success">Selesai</font>
                        @elseif ($item->posisi == 'Ditolak')
                            <font class="text-success">Ditolak</font>
                        @else
                            <font class="text-warning">On Progress</font>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                        @php
                            $pbo = \App\Models\User::select('id')
                                                    ->where('id_cabang', $item->id_cabang)
                                                    ->where('role', 'PBO')
                                                    ->first();
                        @endphp
                        @if ($item->posisi == 'Review Penyelia')
                                <div class="btn-group">
                                    @if (auth()->user()->id_cabang == '1')    
                                        <button type="button" data-toggle="dropdown" class="btn btn-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16" style="color: black">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('pengajuan.detailjawaban',$item->id_pengajuan) }}" class="dropdown-item">Review</a>
                                            <a href="{{ route('pengajuan.backToInputProses',$item->id_pengajuan) }}" class="dropdown-item">Kembalikan Ke Staff</a>
                                            @if ($pbo)
                                                <a href="{{ route('pengajuan.check.pincab',$item->id_pengajuan) }}" class="dropdown-item">Lanjutkan Ke PBO</a>
                                            @else
                                                <a href="{{ route('pengajuan.check.pincab',$item->id_pengajuan) }}" class="dropdown-item">Lanjutkan Ke PBP</a>
                                            @endif
                                            <a target="_blank" href="{{ route('cetak',$item->id_pengajuan) }}" class="dropdown-item" >Cetak</a>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16" style="color: black">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('pengajuan.detailjawaban',$item->id_pengajuan) }}" class="dropdown-item">Review</a>
                                            <a href="{{ route('pengajuan.backToInputProses',$item->id_pengajuan) }}" class="dropdown-item">Kembalikan Ke Staff</a>
                                            @if ($pbo)
                                                <a href="{{ route('pengajuan.check.pincab',$item->id_pengajuan) }}" class="dropdown-item">Lanjutkan Ke PBO</a>
                                            @else
                                                <a href="{{ route('pengajuan.check.pincab',$item->id_pengajuan) }}" class="dropdown-item">Lanjutkan Ke Pincab</a>
                                            @endif
                                            <a target="_blank" href="{{ route('cetak',$item->id_pengajuan) }}" class="dropdown-item" >Cetak</a>
                                        </div>
                                    @endif
                                </div>
                        @else
                            <div class="btn-group">
                                <button type="button" data-toggle="dropdown" class="btn btn-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16" style="color: black">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a target="_blank" href="{{ route('cetak',$item->id_pengajuan) }}" class="dropdown-item" >Cetak</a>
                                    @if ($item->skema_kredit == 'KKB' && $item->posisi == 'Selesai')
                                        @php
                                            $tglCetak = DB::table('log_cetak_kkb')
                                                ->where('id_pengajuan', $item->id_pengajuan)
                                                ->first();
                                        @endphp
                                        @if ($tglCetak?->tgl_cetak_sppk == null || $tglCetak == null)
                                            <a target="_blank" href="{{ route('cetak-sppk',$item->id_pengajuan)}}" class="dropdown-item" >Cetak SPPK</a>
                                        @elseif($tglCetak?->tgl_cetak_sppk != null && $item->sppk == null)
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-id="{{ $item->id_pengajuan }}" data-target="#uploadSPPKModal-{{$item->id_pengajuan}}">Upload File SPPK</a>
                                        @endif

                                        @if ($item->sppk != null && $tglCetak?->tgl_cetak_sppk != null && $tglCetak?->tgl_cetak_po == null)
                                            <a target="_blank" href="{{ route('cetak-po',$item->id_pengajuan)}}" class="dropdown-item" >Cetak PO</a>
                                        @elseif($item->sppk != null && $tglCetak->tgl_cetak_po != null && $item->po == null)
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-id="{{ $item->id_pengajuan }}" data-target="#uploadPOModal-{{$item->id_pengajuan}}">Upload File PO</a>
                                        @endif

                                        @if ($item->po != null && $tglCetak?->tgl_cetak_po != null && $tglCetak?->tgl_cetak_pk == null)
                                            <a target="_blank" href="{{ route('cetak-pk',$item->id_pengajuan)}}" class="dropdown-item" >Cetak PK</a>
                                        @elseif($item->po != null && $tglCetak?->tgl_cetak_pk != null && $item->pk == null)
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-id="{{ $item->id_pengajuan }}" data-target="#uploadPKModal-{{$item->id_pengajuan}}">Upload File PK</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                        </div>
                    </td>
                @empty
                    <td colspan="7" class="text-center" style="background: rgba(71, 145,254,0.05) !important">Data Kosong</td>
                </tr>
                @endforelse
        </tbody>
    </table>
    <div>
        {{$data_pengajuan->links()}}
        Menampilkan 
        {{$data_pengajuan->firstItem()}}
         - 
        {{$data_pengajuan->lastItem()}}
         dari 
        {{$data_pengajuan->total()}} Data
    </div>
    <div class="pull-right">
    </div>
</div>
@endsection
@include('layouts.popup-upload-sppk')
@include('layouts.popup-upload-po')
@include('layouts.popup-upload-pk')
