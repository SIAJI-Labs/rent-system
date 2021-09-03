<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $brandModel;
    private $productModel;
    private $categoryModel;

    /**
     * Instantiate a new ProductController instance.
     * 
     */
    public function __construct()
    {
        $this->brandModel = new \App\Models\Brand();
        $this->productModel = new \App\Models\Product();
        $this->categoryModel = new \App\Models\Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Force Price Column to Integer
        $request->merge([
            'price' => (int) filter_var($request->price, FILTER_SANITIZE_NUMBER_INT)
        ]);

        $request->validate([
            'brand_id' => ['nullable', 'string', 'exists:'.$this->brandModel->getTable().',id'],
            'category_id' => ['nullable', 'string', 'exists:'.$this->categoryModel->getTable().',id'],
            'name' => ['required', 'string', 'max:191'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string']
        ], [
            'brand_id.string' => 'Nilai pada Field Merek tidak valid!',
            'brand_id.exists' => 'Nilai pada Field Merek tidak valid!',
            'category_id.string' => 'Nilai pada Field Kategori tidak valid!',
            'category_id.exists' => 'Nilai pada Field Kategori tidak valid!',
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
            'price.required' => 'Field Biaya Sewa wajib diisi!',
            'price.numeric' => 'Nilai pada Field Biaya Sewa hanya boleh diisi dengan angka!',
            'price.min' => 'Nilai Minimum pada Field Biaya Sewa adalah 0',
            'description.string' => 'Nilai pada Field Deskripsi tidak valid'
        ]);

        \DB::transaction(function () use ($request) {
            $data = $this->productModel;
            $data->category_id = $request->category_id;
            $data->brand_id = $request->brand_id;
            $data->name = $request->name;
            $data->price = $request->price;
            $data->description = $request->description;
            $data->save();
        });

        return redirect()->route('adm.product.index')->with([
            'status' => 'success',
            'message' => 'Data Produk berhasil disimpan'
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
        $data = $this->productModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.product.show', [
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
        $data = $this->productModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.product.edit', [
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
        // Force Price Column to Integer
        $request->merge([
            'price' => (int) filter_var($request->price, FILTER_SANITIZE_NUMBER_INT)
        ]);

        $request->validate([
            'brand_id' => ['nullable', 'string', 'exists:'.$this->brandModel->getTable().',id'],
            'category_id' => ['nullable', 'string', 'exists:'.$this->categoryModel->getTable().',id'],
            'name' => ['required', 'string', 'max:191'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string']
        ], [
            'brand_id.string' => 'Nilai pada Field Merek tidak valid!',
            'brand_id.exists' => 'Nilai pada Field Merek tidak valid!',
            'category_id.string' => 'Nilai pada Field Kategori tidak valid!',
            'category_id.exists' => 'Nilai pada Field Kategori tidak valid!',
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
            'price.required' => 'Field Biaya Sewa wajib diisi!',
            'price.numeric' => 'Nilai pada Field Biaya Sewa hanya boleh diisi dengan angka!',
            'price.min' => 'Nilai Minimum pada Field Biaya Sewa adalah 0',
            'description.string' => 'Nilai pada Field Deskripsi tidak valid'
        ]);

        $data = $this->productModel->where('uuid', $id)->firstOrFail();
        \DB::transaction(function () use ($request, $data) {
            $data->category_id = $request->category_id;
            $data->brand_id = $request->brand_id;
            $data->name = $request->name;
            $data->price = $request->price;
            $data->description = $request->description;
            $data->save();
        });

        return redirect()->route('adm.product.index')->with([
            'status' => 'success',
            'message' => 'Data Produk berhasil diperbaharui'
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
        $data = $this->productModel->query()
            ->select($this->productModel->getTable().'.*');

        if($request->has('brand_id') && $request->brand_id != ''){
            $data->where('brand_id', $request->brand_id);
        }
        if($request->has('category_id') && $request->category_id != ''){
            $data->where('category_id', $request->category_id);
        }

        return datatables()
            ->of($data->with('category', 'brand'))
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
        $data = $this->productModel->query()
            ->select($this->productModel->getTable().'.*');
            // ->where('is_active', true);
        $last_page = null;
        if($request->has('search') && $request->search != ''){
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
        }

        if($request->has('store_id') && $request->store_id != ''){
            $data = $data->whereHas('productDetail', function($q) use ($request){
                return $q->where('store_id', $request->store_id);
            });
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
