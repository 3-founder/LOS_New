<!DOCTYPE html>
{{-- <html lang="en"> --}}
<html ng-app="AngularApp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analisa Kredit | BANK BPR JATIM</title>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/select2/select2.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/sweetalert-master/dist/sweetalert.css" />
    <link href="{{ asset('') }}build/please-wait.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://unpkg.com/popper.js@1.12.8/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tooltip.js@1.3.1/dist/umd/tooltip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

    {{-- <link href="assets/build/css/default.css" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ asset('') }}css/custom.css" />

</head>
<body ng-controller="MainCtrl">
    <div class="inner" ng-view>
    </div>
    <div class="container custom">
        <nav class="navbar navbar-expand-lg py-3 navbar-dark mt-4">
            <div class="container custom">

                <a class="navbar-brand font-weight-bold" href="#"><img src="{{ asset('') }}img/logo.png" width="180px" class="mr-2" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="{{url('/dashboard')}}"><span class="fa fa-home mr-1"></span> Dashboard <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(1) == 'pengajuan-kredit' ? 'active' : '' }}" href="{{url('pengajuan-kredit')}}"><span class="fa fa-credit-card mr-1"></span> Analisa Kredit</a>
                </li>
                @if (auth()->user()->role == 'Administrator')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::segment(1) == 'rekap' ? 'active' : '' }}" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="fa fa-file-alt"></span> Data Master
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('cabang.index') }}">Master Kantor Cabang</a>
                        <a class="dropdown-item" href="{{ route('kabupaten.index') }}">Master Kabupaten</a>
                        <a class="dropdown-item" href="{{ route('kecamatan.index') }}">Master Kecamatan</a>
                        <a class="dropdown-item" href="{{ route('desa.index') }}">Master Desa</a>
                        <a class="dropdown-item" href="{{ route('user.index') }}">Master User</a>
                        <a class="dropdown-item" href="{{ route('master-item.index') }}">Master Item</a>
                    </div>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="fa fa-user"></span> {{auth()->user()->name}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item" href="{{ route('change_password') }}">Ganti Password</a>
                      <a class="dropdown-item logout" href="#" >Logout</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    </div>
                  </li>
            </ul>
        </div>
    </div>
    </nav>
    @yield('dashboard')
    <div class="my-4">
        @if(Request::segment(1) == 'pengajuan-kredit' && (auth()->user()->role == 'Staf Analis Kredit' || auth()->user()->role == 'PBO / PBP'  || auth()->user()->role == 'Penyelia Kredit'))
            @include('layouts.side-card')
        @else
            @include('layouts.full-card')
        @endif
    </div>

</div>
<script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"
  ></script>
<script src="{{ asset('') }}vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('') }}vendor/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="{{ asset('') }}js/select2.full.min.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script> --}}
<script src="{{ asset('') }}build/please-wait.min.js"></script>


<script>
    // $(document).ready(function(){
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(".select2").select2()
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });
        $(".logout").click(function(e){
            e.preventDefault()
            swal({
                    title: "Apakah anda yakin?",
                    text: 'Anda akan keluar dari Aplikasi Analisa Kredit',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: 'Logout',
                    closeOnConfirm: false,
                },
                function() {
                    $("#logout-form").submit()
                }
            );
        })
    // })
    $(".delete").click(function(e){
            e.preventDefault()
            swal({
                    title: "Apakah anda yakin?",
                    text: 'Anda akan menghapus penugasan',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: 'Yakin',
                    closeOnConfirm: false,
                    cancelButtonText: 'Batal',
                },
                function() {
                    $("#delete-penugasan").submit()
                }
            );
            console.log('bisa');
        });

</script>
<script type="text/javascript">
        var loading_screen = pleaseWait({
            logo: "{{ asset('img/logo.png') }}",
            backgroundColor: '#112042',
            loadingHtml: "<div class='spinner'><div class='double-bounce1'></div><div class='double-bounce2'></div><div class='double-bounce3'></div></div>"
        });
        window.loading_screen.finish();
</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
@stack('custom-script')
</body>
</html>
