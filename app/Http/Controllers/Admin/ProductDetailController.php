<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    private $acl = 'product_detail';
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
     * @return \Illuminate\Http\Response
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

    /**
     * Select2 data format, from storage
     * 
     * @param Request $request
     * @return Json
     */
    public function select2(Request $request)
    {
        $data = $this->productDetailModel->query()
            ->select($this->productDetailModel->getTable().'.*');
        $last_page = null;
        if($request->has('search') && $request->search != ''){
            // Apply search param
            $data = $data->where('serial_number', 'like', '%'.$request->search.'%');
        }

        if($request->has('product_id') && $request->product_id != ''){
            $data = $data->where('product_id', $request->product_id);
        }
        if($request->has('store_id') && $request->store_id != ''){
            $data = $data->where('store_id', $request->store_id);
        }
        if($request->has('store_id') && $request->store_id != '' && $request->has('daterange') && $request->daterange != ''){
            $data = $data->whereDoesntHave('transactionItem', function($q) use ($request){                
                return $q->whereHas('transaction', function($q) use ($request){
                    if($request->has('transaction_id') && $request->transaction_id != ''){
                        $q->where('transaction_id', '!=', $request->transaction_id);
                    }
                    
                    return $q->where('store_id', $request->store_id)
                        ->whereIn('status', ['process', 'booking'])
                        ->where(function($q) use ($request){
                            $startDate = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[0]));
                            $endDate = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[1]));

                            return $q->where(function($q) use ($startDate, $endDate){
                                /**
                                 * Start Date: 2021-09-14
                                 * End Date: 2021-09-16
                                 * 
                                 * Rent Case 2021-09-15 ~ 2021-09-17
                                 */
                                return $q->where('start_date', '>=', $startDate)
                                    ->where('start_date', '<=', $endDate);
                            })->orWhere(function($q) use ($startDate, $endDate){
                                /**
                                 * Start Date: 2021-09-14
                                 * End Date: 2021-09-16
                                 * 
                                 * Rent Case 2021-09-13 ~ 2021-09-15
                                 */
                                return $q->where('end_date', '>=', $startDate)
                                    ->where('end_date', '<=', $endDate);
                            })->orWhere(function($q) use ($startDate, $endDate){
                                /**
                                 * Start Date: 2021-09-12
                                 * End Date: 2021-09-16
                                 * 
                                 * Rent Case 2021-09-13 ~ 2021-09-15
                                 */
                                return $q->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                            })->orWhere(function($q) use ($startDate, $endDate){
                                /**
                                 * Start Date: 2021-09-14
                                 * End Date: 2021-09-14
                                 * 
                                 * Rent Case 2021-09-13 ~ 2021-09-15
                                 */
                                return $q->where('start_date', '>=', $startDate)
                                    ->where('end_date', '<=', $endDate);
                            });
                        });
                });
            });
        }

        if($request->has('page')){
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }
        $data->orderBy('created_at', 'asc');

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
