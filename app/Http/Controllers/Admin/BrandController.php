<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private $acl = 'brand';
    private $brandModel;

    /**
     * Instantiate a new BrandController instance.
     * 
     */
    public function __construct()
    {
        $this->brandModel = new \App\Models\Brand();

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
        return view('content.adm.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.brand.create');
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
        ], [
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
        ]);

        // return response()->json($request->all());

        \DB::transaction(function () use ($request) {
            $data = $this->brandModel;
            $data->name = $request->name;
            $data->save();
        });

        return redirect()->route('adm.product.brand.index')->with([
            'status' => 'success',
            'message' => 'Data Merek berhasil disimpan'
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
        $data = $this->brandModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.brand.show', [
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
        $data = $this->brandModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.brand.edit', [
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
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
        ], [
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
        ]);

        $data = $this->brandModel->where('uuid', $id)->firstOrFail();
        \DB::transaction(function () use ($request, $data) {
            $data->name = $request->name;
            $data->save();
        });

        return redirect()->route('adm.product.brand.index')->with([
            'status' => 'success',
            'message' => 'Data Merek berhasil diperbaharui'
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
     * Datatable data format, from storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function datatableAll(Request $request)
    {
        $data = $this->brandModel->query()
            ->select($this->brandModel->getTable().'.*');

        return datatables()
            ->of($data->withCount('product'))
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
        $data = $this->brandModel->query()
            ->select($this->brandModel->getTable().'.*');
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
