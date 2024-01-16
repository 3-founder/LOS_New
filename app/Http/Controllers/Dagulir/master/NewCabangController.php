<?php

namespace App\Http\Controllers\Dagulir\master;

use App\Http\Controllers\Controller;

use App\Http\Requests\CabangRequest;
use Illuminate\Http\Request;
use \App\Models\Cabang;
use \App\Models\Kabupaten;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewCabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $param;

    public function __construct()
    {
        $this->param['pageIcon'] = 'fa fa-database';
        $this->param['parentMenu'] = '/cabang';
        $this->param['current'] = 'Kantor Cabang';
    }
    public function index(Request $request)
    {
        $this->param['pageTitle'] = 'List Kantor Cabang';
        $this->param['btnText'] = 'Tambah Cabang';
        $this->param['btnLink'] = route('cabang.create');

        try {
            $search = $request->get('q');
            $limit = $request->has('page_length') ? $request->get('page_length') : 10;
            $page = $request->has('page') ? $request->get('page') : 1;
            $getCabang = Cabang::orderBy('kode_cabang', 'ASC');

            if ($search) {
                $getCabang->where('kode_cabang', 'LIKE', "%{$search}%")
                ->orWhere('cabang', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            }

            $this->param['data'] = $getCabang->paginate($limit);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi Kesalahan : ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->withError('Terjadi Kesalahan : ' . $e->getMessage());
        }

        return \view('dagulir.master.cabang.index', $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageTitle'] = 'Tambah Kantor Cabang';
        $this->param['btnText'] = 'List Kantor Cabang';
        $this->param['btnLink'] = route('cabang.index');


        return \view('cabang.create', $this->param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        try {
            if ($request->kode_cabang == null) {
                alert()->error('error', 'Kode Cabang harus diisi.');
                return back();
            }
            if ($request->cabang == null) {
                alert()->error('error', 'Nama Cabang harus diisi.');
                return back();
            }
            if ($request->email == null) {
                alert()->error('error', 'Email Cabang harus diisi.');
                return back();
            }
            if ($request->alamat == null) {
                alert()->error('error', 'Alamat Cabang harus diisi.');
                return back();
            }
            $data = Cabang::where('kode_cabang', 'LIKE', "%{$request->kode_cabang}%")
            ->where('cabang', 'LIKE', "%{$request->cabang}%")
            ->Where('email', 'LIKE', "%{$request->email}%")
            ->Where('alamat', 'LIKE', "%{$request->alamat}%")
            ->first();

            if ($data) {
                alert()->error('error', 'Cabang sudah ada.');
                return back();
            }

            $cabang = new Cabang;
            $cabang->kode_cabang = $request->kode_cabang;
            $cabang->cabang = $request->cabang;
            $cabang->email = $request->email;
            $cabang->alamat = $request->alamat;
            $cabang->save();
            alert()->success('Success', 'Berhasil menambahkan data.');
            return redirect()->route('dagulir.master.cabang.index');
        } catch (Exception $e) {
            return back()->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            return back()->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->param['pageTitle'] = 'Edit Kantor Cabang';
        $this->param['cabang'] = Cabang::find($id);
        $this->param['btnText'] = 'List Cabang';
        $this->param['btnLink'] = route('cabang.index');

        return view('cabang.edit', $this->param);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kode_cabang = Cabang::find($request->get('id'));
        $isUniqueKodeCabang = $kode_cabang->kode_cabang == $request->kode_cabang ? '' : '|unique:cabang,kode_cabang';
        $isUniqueEmail = $kode_cabang->email == $request->email ? '' : '|unique:cabang,email';

        $validatedData = Validator::make($request->all(), [
            'kode_cabang' => 'required' . $isUniqueKodeCabang,
            'cabang' => 'required',
            'email' => 'required' . $isUniqueEmail,
            'alamat' => 'required',
        ]);

        if ($validatedData->fails()) {
            $html = "<ul style='list-style: none;'>";
            foreach($validatedData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ul>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error');
            return redirect()->route('dagulir.master.cabang.index');
        }

        try {
            $cabang = Cabang::findOrFail($request->get('id'));
            $cabang->kode_cabang = $request->get('kode_cabang');
            $cabang->cabang = $request->get('cabang');
            $cabang->email = $request->get('email');
            $cabang->alamat = $request['alamat'];
            $cabang->save();

            alert()->success('Berhasil','Data berhasil diperbarui.');
            return redirect()->route('dagulir.master.cabang.index');
        } catch (\Exception $e) {
            alert()->error('Error','Terjadi Kesalahan.');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            alert()->error('Error','Terjadi Kesalahan.');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cabang = Cabang::findOrFail($id);
            $cabang->delete();
            alert()->success('Berhasil','Data berhasil dihapus.');
            return redirect()->route('dagulir.master.cabang.index');
        } catch (Exception $e) {
            alert()->error('Error','Terjadi Kesalahan.');
            return back()->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            alert()->error('Error','Terjadi Kesalahan.');
            return back()->withError('Terjadi kesalahan.');
        }

    }
}
