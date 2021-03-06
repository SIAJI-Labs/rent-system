<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private $acl = 'store';
    private $storeModel;
    /**
     * Instantiate a new StoreController instance.
     * 
     */
    public function __construct()
    {
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
        return view('content.adm.store.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.store.create');
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
            'name' => ['required', 'string', 'max:191'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'invoice_prefix' => ['required', 'string', 'max:6', 'unique:'.$this->storeModel->getTable().',invoice_prefix', 'regex:/^[A-Z]+$/'],
            'note' => ['nullable', 'string'],
        ], [
            'name.required' => 'Field Nama Toko wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Toko tidak valid!',
            'name.max' => 'Nilai pada Field Nama Toko melebihi batas jumlah karakter (191)!',
            'address.string' => 'Nilai pada Field Alamat tidak valid!',
            'latitude.string' => 'Nilai pada Field Latitude tidak valid!',
            'longitude.string' => 'Nilai pada Field Longitude tidak valid!',
            'note.string' => 'Nilai pada Field Catatan tidak valid!',
            'invoice_prefix.required' => 'Field Prefix Invoice wajib diisi!',
            'invoice_prefix.string' => 'Nilai pada Field Prefix Invoice tidak valid!',
            'invoice_prefix.max' => 'Nilai pada Field Prefix Invoice melebihi batas jumlah karakter (191)!',
            'invoice_prefix.unique' => 'Nilai pada Field Prefix Invoice sudah digunakan!',
            'invoice_prefix.regex' => 'Nilai pada Field Prefix Invoice hanya dapat diisi oleh karakter A-Z!',
        ]);

        \DB::transaction(function () use ($request) {
            $data = $this->storeModel;
            $data->name = $request->name;
            $data->phone = $request->phone ?? null;
            $data->address = $request->address;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->invoice_prefix = $request->invoice_prefix;
            $data->chart_hex_color = $request->chart_color;
            $data->chart_rgb_color = convertHexToRgb($request->chart_color);
            $data->is_active = true;
            $data->save();
        });

        return redirect()->route('adm.store.index')->with([
            'status' => 'success',
            'message' => 'Data Toko berhasil disimpan'
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
        $data = $this->storeModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.store.show', [
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
        $data = $this->storeModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.store.edit', [
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
        $data = $this->storeModel->where('uuid', $id)->firstOrFail();
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'invoice_prefix' => ['required', 'string', 'max:6', 'unique:'.$this->storeModel->getTable().',invoice_prefix,'.$data->id, 'regex:/^[A-Z]+$/'],
        ], [
            'name.required' => 'Field Nama Toko wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Toko tidak valid!',
            'name.max' => 'Nilai pada Field Nama Toko melebihi batas jumlah karakter (191)!',
            'address.string' => 'Nilai pada Field Alamat tidak valid!',
            'latitude.string' => 'Nilai pada Field Latitude tidak valid!',
            'longitude.string' => 'Nilai pada Field Longitude tidak valid!',
            'note.string' => 'Nilai pada Field Catatan tidak valid!',
            'invoice_prefix.required' => 'Field Prefix Invoice wajib diisi!',
            'invoice_prefix.string' => 'Nilai pada Field Prefix Invoice tidak valid!',
            'invoice_prefix.max' => 'Nilai pada Field Prefix Invoice melebihi batas jumlah karakter (191)!',
            'invoice_prefix.unique' => 'Nilai pada Field Prefix Invoice sudah digunakan!',
            'invoice_prefix.regex' => 'Nilai pada Field Prefix Invoice hanya dapat diisi oleh karakter A-Z!',
        ]);

        \DB::transaction(function () use ($request, $data) {
            $data->name = $request->name;
            $data->phone = $request->phone ?? null;
            $data->address = $request->address;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->invoice_prefix = $request->invoice_prefix;
            $data->chart_hex_color = $request->chart_color;
            $data->chart_rgb_color = convertHexToRgb($request->chart_color);
            $data->is_active = true;
            $data->save();
        });

        return redirect()->route('adm.store.index')->with([
            'status' => 'success',
            'message' => 'Data Toko berhasil diperbaharui'
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
        $data = $this->storeModel->query()
            ->select($this->storeModel->getTable().'.*');

        return datatables()
            ->of($data)
            ->toJson();
    }

    /**
     * Select2 data format, from storage
     * 
     * @param Request $request
     * @return Json
     */
    public function select2(Request $request)
    {
        $data = $this->storeModel->query()
            ->select($this->storeModel->getTable().'.*');
        $last_page = null;
        if($request->has('search') && $request->search != ''){
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
        }

        if($request->has('page')){
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }
        $data->orderBy('name', 'asc');

        return response()->json([
            'message' => 'Data Fetched',
            'data' => $data->get()->each(function($data){
                $data->makeVisible('id');
            }),
            'extra_data' => [
                'last_page' => $last_page,
            ]
        ]);
    }
}
