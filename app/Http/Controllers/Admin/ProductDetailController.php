<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    private $storeModel;
    private $productModel;
    private $productDetailModel;
    /**
     * Instantiate a new ProductDetailController instance.
     * 
     */
    public function __construct()
    {
        $this->storeModel = new \App\Models\Store();
        $this->productModel = new \App\Models\Product();
        $this->productDetailModel = new \App\Models\ProductDetail();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($product_id)
    {
        $product = $this->productModel->where('uuid', $product_id)
            ->firstOrFail();
            
        return view('content.adm.product.serial-number.create', [
            'product' => $product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $product_id)
    {
        $product = $this->productModel->where('uuid', $product_id)
            ->firstOrFail();

        // Validate Request
        $request->validate([
            'store_id' => ['required', 'string', 'exists:'.$this->storeModel->getTable().',id'],
            'serial_number' => ['required', 'string', 'max:191', 'unique:'.$this->productDetailModel->getTable().',serial_number'],
            'note' => ['nullable', 'string']
        ], [
            'store_id.required' => 'Field Toko wajib diisi!',
            'store_id.string' => 'Nilai pada Field Toko tidak valid!',
            'store_id.exists' => 'Nilai pada Field Toko tidak tersedia!',
            'serial_number.required' => 'Field Serial Number wajib diisi!',
            'serial_number.string' => 'Nilai pada Field Serial Number tidak valid!',
            'serial_number.max' => 'Nilai pada Field Serial Number melebihi batas jumlah karakter (191)!',
            'serial_number.unique' => 'Nilai pada Field Serial Number sudah digunakan!',
            'serial_number.note' => 'Nilai pada Field Serial Number tidak valid!',
        ]);

        \DB::transaction(function () use ($request, $product) {
            $data = $this->productDetailModel;
            $data->store_id = $request->store_id;
            $data->product_id = $product->id;
            $data->serial_number = $request->serial_number;
            $data->status = true;
            $data->note = $request->note;
            $data->save();
        });

        return redirect()->route('adm.product.show', $product->uuid)->with([
            'status' => 'success',
            'message' => 'Berhasil menambahkan serial number untuk produk '.$product->name
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($product_id, $id)
    {
        $product = $this->productModel->where('uuid', $product_id)
            ->firstOrFail();
        $data = $this->productDetailModel->where('uuid', $id)
            ->firstOrFail();

        return view('content.adm.product.serial-number.edit', [
            'product' => $product,
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
        //
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
     * @return 
     */
    public function datatableAll(Request $request, $product_id)
    {
        $product = $this->productModel->where('uuid', $product_id)
            ->firstOrFail();

        $data = $this->productDetailModel->query()
            ->select($this->productDetailModel->getTable().'.*')
            ->where('product_id', $product->id);

        if($request->has('store_id') && $request->store_id != ''){
            $data->where('store_id', $request->store_id);
        }

        return datatables()
            ->of($data->with('store'))
            ->toJson();
    }
}
