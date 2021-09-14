<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    private $acl = 'staff';
    private $staffModel;
    private $storeModel;

    /**
     * Instantiate a new StaffController instance.
     * 
     */
    public function __construct()
    {
        $this->staffModel = new \App\Models\Admin();
        $this->storeModel = new \App\Models\Store();

        // Spatie ACL
        $this->middleware('permission:'.$this->acl.'-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:'.$this->acl.'-create', ['only' => ['create','store']]);
        $this->middleware('permission:'.$this->acl.'-edit', ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => ['nullable', 'string', 'exists:'.$this->storeModel->getTable().',id'],
            'name' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'max:10', 'unique:'.$this->staffModel->getTable().',username'],
            'email' => ['required', 'email', 'unique:'.$this->staffModel->getTable().',email'],
        ], [
            'store_id.string' => 'Nilai pada Field Toko tidak valid!',
            'store_id.exists' => 'Nilai pada Field Toko tidak tersedia!',
            'name.required' => 'Field Nama wajib diisi!',
            'name.string' => 'Nilai pada Field Nama tidak valid!',
            'name.max' => 'Nilai pada Field Nama melebihi batas jumlah karakter (191)!',
            'username.required' => 'Field Username wajib diisi!',
            'username.string' => 'Nilai pada Field Username tidak valid!',
            'username.max' => 'Nilai pada Field Username melebihi batas jumlah karakter (10)!',
            'username.unique' => 'Nilai pada Field Username sudah digunakan!',
            'email.required' => 'Field Email wajib diisi!',
            'email.email' => 'Nilai pada Field Email tidak valid!',
            'email.unique' => 'Nilai pada Field Email sudah digunakan!',
        ]);

        \DB::transaction(function () use ($request) {
            $password = generateRandomString();

            $data = $this->staffModel;
            $data->store_id = $request->store_id;
            $data->name = $request->name;
            $data->username = $request->username;
            $data->email = $request->email;
            $data->password = bcrypt($password);
            $data->raw_password = saEncryption($password);
            $data->is_active = $request->is_active == 'on' ? true : false;
            $data->is_admin = $request->is_admin == 'on' ? true : false;
            $data->save();
        });

        return redirect()->route('adm.staff.index')->with([
            'status' => 'success',
            'message' => 'Data Staff berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->staffModel->where('uuid', $id)
            ->firstOrFail();
        return view('content.adm.staff.show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->staffModel->where('uuid', $id)
            ->firstOrFail();
        return view('content.adm.staff.edit', [
            'data' => $data
        ]);
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
        $data = $this->staffModel->where('uuid', $id)
            ->firstOrFail();

        $request->validate([
            'store_id' => ['nullable', 'string', 'exists:'.$this->storeModel->getTable().',id'],
            'name' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'max:10', 'unique:'.$this->staffModel->getTable().',username,'.$data->id],
            'email' => ['required', 'email', 'unique:'.$this->staffModel->getTable().',email,'.$data->id],
        ], [
            'store_id.string' => 'Nilai pada Field Toko tidak valid!',
            'store_id.exists' => 'Nilai pada Field Toko tidak tersedia!',
            'name.required' => 'Field Nama wajib diisi!',
            'name.string' => 'Nilai pada Field Nama tidak valid!',
            'name.max' => 'Nilai pada Field Nama melebihi batas jumlah karakter (191)!',
            'username.required' => 'Field Username wajib diisi!',
            'username.string' => 'Nilai pada Field Username tidak valid!',
            'username.max' => 'Nilai pada Field Username melebihi batas jumlah karakter (10)!',
            'username.unique' => 'Nilai pada Field Username sudah digunakan!',
            'email.required' => 'Field Email wajib diisi!',
            'email.email' => 'Nilai pada Field Email tidak valid!',
            'email.unique' => 'Nilai pada Field Email sudah digunakan!',
        ]);

        \DB::transaction(function () use ($request, $data) {
            $data->store_id = $request->store_id;
            $data->name = $request->name;
            $data->username = $request->username;
            $data->email = $request->email;
            $data->is_active = $request->is_active == 'on' ? true : false;
            $data->is_admin = $request->is_admin == 'on' ? true : false;
            $data->save();
        });

        return redirect()->route('adm.staff.index')->with([
            'status' => 'success',
            'message' => 'Data Staff berhasil diperbaharui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Datatable data from storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function datatableAll(Request $request)
    {
        $data = $this->staffModel->query()
            ->select($this->staffModel->getTable().'.*')
            ->whereNotIn('id', [\Auth::guard('admin')->user()->id]);

        return datatables()
            ->of($data->with('store'))
            ->toJson();
    }
}
