<div class="table-responsive">
    <table class="table table-hover table-custom">
        <thead>
            <tr class="table-primary">
                <th class="text-center">#</th>
                <th>Ip Address</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Cabang</th>
                <th>Aplikasi</th>
                <th>Lama Login</th>
                <th>IP</th>
                <th>Perangkat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $page = Request::get('page');
                $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
            @endphp
            @foreach ($data as $key => $item)
                {{-- @php
                    $cabang = '-';
                    if ($item->id_cabang) {
                        $dataCabang = DB::table('cabang')
                            ->where('id', $item->id_cabang)
                            ->first();
                        $cabang = $dataCabang->cabang;
                    }
                    // hitung berapa lama login
                    $start = strtotime($item->created_at);
                    $end = strtotime(date('Y-m-d H:i:s'));

                    // convert seconds to hours
                    $hours = intval(($end - $start)/3600);
                    // convert seconds to minutes
                    $mins = (int)(($end - $start) / 60);
                    // formating seconds
                    $secs = explode('.', number_format((float)(($end - $start) / 60), 2))[1];
                    $secs = $secs > 60 ? 0 : $secs;
                @endphp --}}
                @php
                    $cabang = '-';
                    if ($item->id_cabang) {
                        $dataCabang = DB::table('cabang')
                            ->where('id', $item->id_cabang)
                            ->first();
                        $cabang = $dataCabang->cabang;
                    }
                
                    // Waktu login pengguna
                    $startTime = new DateTime($item->created_at);
                
                    // Waktu saat ini
                    $endTime = new DateTime('now');
                
                    // Hitung perbedaan waktu
                    $interval = $endTime->diff($startTime);
                
                    // Format waktu
                    $hours = $interval->h;
                    $minutes = $interval->i;
                    $seconds = $interval->s;
                @endphp
                <tr class="border-bottom-primary">
                    <td class="text-center text-muted">{{ $no }}</td>
                    <td>{{ $item->ip_address }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @if ($item->karyawan)
                            {{ array_key_exists('nama', $item->karyawan) ? $item->karyawan['nama'] : '-' }}
                        @else
                            {{ property_exists($item, 'name') ? $item->name : '-' }}
                        @endif
                    </td>
                    <td>{{ $item->role }}</td>
                    <td>{{ $cabang }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $item->project)) }}</td>
                    <td>
                        <span class="clock_{{$item->id}}"></span>
                        <script>
                            currentTime({{$hours}}, {{$minutes}}, {{$seconds}}, "clock_{{$item->id}}")
                            function currentTime(h, m, s, widget_id) {
                                let hh = parseInt(h);
                                let mm = parseInt(m);
                                let ss = parseInt(s);
                                ss++;
                    
                                if (ss > 59) {
                                    mm++;
                                    ss = 0;
                                }
                    
                                if (mm > 59) {
                                    hh++;
                                    mm = 0;
                                }
                    
                                hh = (hh < 10) ? "0" + hh : hh;
                                mm = (mm < 10) ? "0" + mm : mm;
                                ss = (ss < 10) ? "0" + ss : ss;
                    
                                let time = hh + ":" + mm + ":" + ss;
                                document.querySelector(`.${widget_id}`).innerHTML = time;
                                var t = setTimeout(function(){ currentTime(hh, mm, ss, `${widget_id}`) }, 1000); 
                            }
                        </script>
                    </td>
                    <td>{{ $item->ip_address }}</td>
                    <td>{{ $item->device_name }}</td>
                    <td><h5 class="badge badge-info">Aktif</h5></td>
                    <td>
                        <form action="{{ route('reset-api-session', $item->id) }}" method="post">
                            @csrf
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmResetApiSession-{{$key}}">
                                Reset
                            </button>
                            @include('user.api-sessions.confirm-modal')
                        </form>
                    </td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        {{ $data->appends(Request::all())->links('vendor.pagination.custom') }}
    </div>
</div>
