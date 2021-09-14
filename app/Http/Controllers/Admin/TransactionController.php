<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $acl = 'transaction';
    private $storeModel;
    private $productModel;
    private $customerModel;
    private $accountingModel;
    private $transactionModel;
    private $productDetailModel;
    private $transactionLogModel;
    private $transactionItemModel;

    /**
     * Instantiate a new TransactionController instance.
     * 
     */
    public function __construct()
    {
        $this->storeModel = new \App\Models\Store();
        $this->productModel = new \App\Models\Product();
        $this->customerModel = new \App\Models\Customer();
        $this->accountingModel = new \App\Models\Accounting();
        $this->transactionModel = new \App\Models\Transaction();
        $this->productDetailModel = new \App\Models\ProductDetail();
        $this->transactionLogModel = new \App\Models\TransactionLog();
        $this->transactionItemModel = new \App\Models\TransactionItem();

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
        return view('content.adm.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_arr = $request->product;
        foreach($request->product as $key => $value){
            $product_arr[$key]['price'] = (int) filter_var($value['price'], FILTER_SANITIZE_NUMBER_INT);
            $product_arr[$key]['discount'] = (int) filter_var($value['discount'], FILTER_SANITIZE_NUMBER_INT);
        }

        $store_id = $request->store_id;
        if(\Auth::guard('admin')->check() && !empty(\Auth::guard('admin')->user()->store_id)){
            $store_id = (string) \Auth::guard('admin')->user()->store_id;
        }

        $request->merge([
            'product' => $product_arr,
            'sum_price' => (int) filter_var($request->sum_price, FILTER_SANITIZE_NUMBER_INT),
            'sum_discount' => (int) filter_var($request->sum_discount, FILTER_SANITIZE_NUMBER_INT),
            'periode' => (int) filter_var($request->periode, FILTER_SANITIZE_NUMBER_INT),
            'extra_amount' => (int) filter_var($request->extra_amount, FILTER_SANITIZE_NUMBER_INT),
            'sum_amount' => (int) filter_var($request->sum_amount, FILTER_SANITIZE_NUMBER_INT),
            'paid' => (int) filter_var($request->paid, FILTER_SANITIZE_NUMBER_INT),
            'leftover' => (int) filter_var($request->leftover, FILTER_SANITIZE_NUMBER_INT),
            'store_id' => $store_id
        ]);

        $request->validate([
            'store_id' => ['required', 'string', 'exists:'.$this->storeModel->getTable().',id'],
            'customer_id' => ['required', 'string', 'exists:'.$this->customerModel->getTable().',id'],
            'daterange' => ['required', 'string'],
            'status' => ['required', 'string', 'in:process,booking,complete,cancel'],
            'note' => ['nullable', 'string'],
            'product.*.product_id' => ['required', 'string', 'exists:'.$this->productModel->getTable().',id'],
            'product.*.sn_id' => ['required', 'string', 'exists:'.$this->productDetailModel->getTable().',id', 'distinct'],
            'product.*.price' => ['required', 'numeric', 'min:0'],
            'product.*.discount' => ['nullable', 'numeric', 'min:0'],
            'product.*.note' => ['nullable', 'string']
        ], [
            'store_id.required' => 'Field Toko wajib diisi!', 
            'store_id.string' => 'Nilai pada Field Toko tidak valid!', 
            'store_id.exists' => 'Nilai pada Field Toko tidak tersedia!', 
            'customer_id.required' => 'Field Kostumer wajib diisi!', 
            'customer_id.string' => 'Nilai pada Field Kostumer tidak valid!', 
            'customer_id.exists' => 'Nilai pada Field Kostumer tidak tersedia!', 
            'daterange.required' => 'Field Periode Sewa wajib diisi!',
            'daterange.string' => 'Nilai pada Field Periode Sewa tidak valid!',
            'type.required' => 'Field Jenis Transaksi wajib diisi!', 
            'type.string' => 'Nilai pada Field Jenis Transaksi tidak valid!', 
            'type.in' => 'Nilai pada Field Jenis Transaksi tidak tersedia!', 
            'note.string' => 'Nilai pada Field Catatan tidak valid!',
            'product.*.product_id.required' => 'Field Produk wajib diisi!', 
            'product.*.product_id.string' => 'Nilai pada Field Produk tidak valid!', 
            'product.*.product_id.exists' => 'Nilai pada Field Produk tidak tersedia!', 
            'product.*.sn_id.required' => 'Field SN Produk wajib diisi!', 
            'product.*.sn_id.string' => 'Nilai pada Field SN Produk tidak valid!', 
            'product.*.sn_id.exists' => 'Nilai pada Field SN Produk tidak tersedia!',
            'product.*.sn_id.distinct' => 'Terdapat nilai duplikat pada Field SN Produk!',
            'product.*.price.required' => 'Field Biaya Sewa Produk wajib diisi!',
            'product.*.price.numeric' => 'Nilai pada Field Biaya Sewa Produk hanya dapat diisi dengan angka!',
            'product.*.price.min' => 'Nilai minimum pada Field Biaya Sewa Produk adalah 0!',
            'product.*.discount.numeric' => 'Nilai pada Field Biaya Sewa Produk hanya dapat diisi dengan angka!',
            'product.*.discount.min' => 'Nilai minimum pada Field Biaya Sewa Produk adalah 0!',
            'product.*.note.string' => 'Nilai pada Field Catatan Produk tidak valid!', 
        ]);

        // Validate if status is complete
        if($request->status == "complete" && $request->leftover > 0){
            return redirect()->back()->withInput()->withErrors([
                'paid' => ['Data Transaksi tidak dapat disimpan karena transaksi diatur sebagai "Selesai" namun pembayaran belum lunas']
            ]);
        }

        \DB::transaction(function () use ($request) {
            $customer = $this->customerModel->findOrFail($request->customer_id);

            // Product
            $sum_price = 0;
            $sum_discount = 0;
            $transactionProductArr = [];
            foreach($request->product as $product){
                $transactionProductArr[] = new \App\Models\TransactionItem([
                    'product_id' => $product['product_id'],
                    'product_detail_id' => $product['sn_id'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'note' => $product['note']
                ]);

                $sum_price += $product['price'];
                $sum_discount += $product['discount'];
            }

            $store = $this->storeModel->findOrFail($request->store_id);
            do {
                $invoice = generateInvoice($store->invoice_prefix);
                $check = $this->transactionModel->where('invoice', $invoice)->first();
            } while($check);

            $data = $this->transactionModel;
            $data->user_id = \Auth::user()->id;
            $data->store_id = $request->store_id;
            $data->customer_id = $customer->id;
            $data->invoice = $invoice;
            $data->date = date("Y-m-d H:i:s");
            $data->start_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[0]));
            $data->end_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[1]));
            $data->must_end_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[1]));
            $data->back_date = null;
            $data->amount = ($sum_price * $request->periode);
            $data->discount = ($sum_discount * $request->periode);
            $data->paid = $request->paid;
            // $data->charge = $request->store_id;
            $data->extra = $request->extra_amount;
            $data->status = $request->status;
            $data->note = $request->note;
            $data->save();

            if(!empty($transactionProductArr)){
                $data->transactionItem()->saveMany($transactionProductArr);
            }

            // Add TransactionLog
            $transactionLog = $this->transactionLogModel->create([
                'transaction_id' => $data->id,
                'user_id' => \Auth::user()->id,
                'date' => date("Y-m-d H:i:s"),
                'log' => 'User membuat data transaksi via dashboard'
            ]);
            // Get Last Audit on Transaction
            $lastAudit = $data->audits()->get()->last();
            $lastAudit->extra_type = get_class($transactionLog);
            $lastAudit->extra_id = $transactionLog->id;
            $lastAudit->save();
            // Get Audit on Transaction Item
            foreach($data->transactionItem as $item){
                $lastAuditItem = $item->audits()->get()->last();
                $lastAuditItem->extra_type = get_class($transactionLog);
                $lastAuditItem->extra_id = $transactionLog->id;
                $lastAuditItem->save();
            }

            // Send an Email
            if(!empty($customer->email) && $request->has('invoiceEmail')){
                $mail = $customer->email;
                $mailable = new \App\Mail\Transaction\TransactionCheckout($data);
                $mailJob = dispatch(new \App\Jobs\SendEmailJob($mail, $mailable))
                    ->delay(\Carbon\Carbon::now()->addSeconds(10)); // Add some delay
            }
        });

        return redirect()->route('adm.transaction.index')->with([
            'status' => 'success',
            'message' => "Data Transaksi berhasil ditambahkan"
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
        $data = $this->transactionModel->where('uuid', $id)
            ->firstOrFail();
        $diff = $data->audits()->with('user')->get()->last();

        // return response()->json([
        //     'audit' => $diff->getModified()
        // ]);
        return view('content.adm.transaction.show', [
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
        $data = $this->transactionModel->where('uuid', $id)
            ->firstOrFail();
        return view('content.adm.transaction.edit', [
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
        $product_arr = $request->product;
        foreach($request->product as $key => $value){
            $product_arr[$key]['price'] = (int) filter_var($value['price'], FILTER_SANITIZE_NUMBER_INT);
            $product_arr[$key]['discount'] = (int) filter_var($value['discount'], FILTER_SANITIZE_NUMBER_INT);
        }
        $request->merge([
            'product' => $product_arr,
            'sum_price' => (int) filter_var($request->sum_price, FILTER_SANITIZE_NUMBER_INT),
            'sum_discount' => (int) filter_var($request->sum_discount, FILTER_SANITIZE_NUMBER_INT),
            'periode' => (int) filter_var($request->periode, FILTER_SANITIZE_NUMBER_INT),
            'extra_amount' => (int) filter_var($request->extra_amount, FILTER_SANITIZE_NUMBER_INT),
            'sum_amount' => (int) filter_var($request->sum_amount, FILTER_SANITIZE_NUMBER_INT),
            'paid' => (int) filter_var($request->paid, FILTER_SANITIZE_NUMBER_INT),
            'leftover' => (int) filter_var($request->leftover, FILTER_SANITIZE_NUMBER_INT),
        ]);

        $request->validate([
            'daterange' => ['required', 'string'],
            'note' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:process,booking,complete,cancel'],
            'product.*.product_id' => ['required', 'string', 'exists:'.$this->productModel->getTable().',id'],
            'product.*.sn_id' => ['required', 'string', 'exists:'.$this->productDetailModel->getTable().',id', 'distinct'],
            'product.*.price' => ['required', 'numeric', 'min:0'],
            'product.*.discount' => ['nullable', 'numeric', 'min:0'],
            'product.*.note' => ['nullable', 'string']
        ], [
            'daterange.required' => 'Field Periode Sewa wajib diisi!',
            'daterange.string' => 'Nilai pada Field Periode Sewa tidak valid!',
            'note.string' => 'Nilai pada Field Catatan tidak valid!',
            'product.*.product_id.required' => 'Field Produk wajib diisi!', 
            'product.*.product_id.string' => 'Nilai pada Field Produk tidak valid!', 
            'product.*.product_id.exists' => 'Nilai pada Field Produk tidak tersedia!', 
            'product.*.sn_id.required' => 'Field SN Produk wajib diisi!', 
            'product.*.sn_id.string' => 'Nilai pada Field SN Produk tidak valid!', 
            'product.*.sn_id.exists' => 'Nilai pada Field SN Produk tidak tersedia!',
            'product.*.sn_id.distinct' => 'Terdapat nilai duplikat pada Field SN Produk!',
            'product.*.price.required' => 'Field Biaya Sewa Produk wajib diisi!',
            'product.*.price.numeric' => 'Nilai pada Field Biaya Sewa Produk hanya dapat diisi dengan angka!',
            'product.*.price.min' => 'Nilai minimum pada Field Biaya Sewa Produk adalah 0!',
            'product.*.discount.numeric' => 'Nilai pada Field Biaya Sewa Produk hanya dapat diisi dengan angka!',
            'product.*.discount.min' => 'Nilai minimum pada Field Biaya Sewa Produk adalah 0!',
            'product.*.note.string' => 'Nilai pada Field Catatan Produk tidak valid!', 
        ]);

        // Validate if status is complete
        if($request->status == "complete" && $request->leftover > 0){
            return redirect()->back()->withInput()->withErrors([
                'paid' => ['Data Transaksi tidak dapat disimpan karena transaksi diatur sebagai "Selesai" namun pembayaran belum lunas']
            ]);
        }

        $data = $this->transactionModel->where('uuid', $id)
            ->firstOrFail();
        $dbTransaction = \DB::transaction(function () use ($request, $data) {
            // Add TransactionLog
            $transactionLog = $this->transactionLogModel->create([
                'transaction_id' => $data->id,
                'user_id' => \Auth::user()->id,
                'date' => date("Y-m-d H:i:s"),
                'log' => 'User melakukan perubahan data transaksi via dashboard'
            ]);

            // Product
            $sum_price = 0;
            $sum_discount = 0;
            $transactionProductArr = [];
            $errors = [];
            $existsOnDb = [];
            foreach($request->product as $key => $product){
                // Validate Item Schedule
                $checkSchedule = $this->transactionItemModel->where('product_id', $product['product_id'])
                    ->where('product_detail_id', $product['sn_id'])
                    ->where('transaction_id', '!=', $data->id)
                    ->whereHas('transaction', function($q) use ($request, $data){
                        return $q->where('store_id', $data->store_id)
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
                    })
                    ->first();
                if(!empty($checkSchedule)){
                    // Schedule Exists
                    $errors[] = [
                        'key' => $key,
                        'type' => 'product',
                        'sub_type' => 'sn_id',
                        'product' => $checkSchedule->product->name,
                        'product_sn' => $checkSchedule->productDetail->serial_number,
                    ];
                }

                if(isset($product['validate'])){
                    $transactionItem = $this->transactionItemModel->findOrFail($product['validate']);
                    $transactionItem->product_id = $product['product_id'];
                    $transactionItem->product_detail_id = $product['sn_id'];
                    $transactionItem->price = $product['price'];
                    $transactionItem->discount = $product['discount'];
                    $transactionItem->note = $product['note'];
                    $transactionItem->save();

                    $existsOnDb[] = $transactionItem->id;
                } else {
                    $transactionItem = $this->transactionItemModel->create([
                        'transaction_id' => $data->id,
                        'product_id' => $product['product_id'],
                        'product_detail_id' => $product['sn_id'],
                        'price' => $product['price'],
                        'discount' => $product['discount'],
                        'note' => $product['note']
                    ]);
                }

                // Get Last Audit on Transaction Item
                if($transactionItem->wasChanged()){
                    $lastAuditItem = $transactionItem->audits()->get()->last();
                    $lastAuditItem->extra_type = get_class($transactionLog);
                    $lastAuditItem->extra_id = $transactionLog->id;
                    $lastAuditItem->save();
                }

                $sum_price += $product['price'];
                $sum_discount += $product['discount'];
            }
            // Validate if there's some error
            if(!empty($errors)){
                $temp_error = [];
                foreach($errors as $error){
                    $temp_error[$error['type'].'.'.$error['key'].'.'.$error['sub_type']] = ['Ada jadwal yang bermasalah, mohon periksa kembali jadwal sewa untuk produk '.$error['product'].' dengan SN '.$error['product_sn']];
                }
                $errorException = \Illuminate\Validation\ValidationException::withMessages($temp_error);
                throw $errorException;
            }

            $data->start_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[0]));
            $data->end_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[1]));
            $data->must_end_date = date("Y-m-d H:i:00", strtotime(explode('-', $request->daterange)[1]));
            $data->amount = ($sum_price * $request->periode);
            $data->discount = ($sum_discount * $request->periode);
            $data->paid = $request->paid;
            // $data->charge = $request->store_id;
            $data->extra = $request->extra_amount;
            $data->status = $request->status;
            $data->note = $request->note;
            $data->save();

            // Check if there's delete action
            $checkTransactionItemDelete = $this->transactionItemModel->where('transaction_id', $data->id)
                ->whereNotIn('id', $existsOnDb)
                ->get();
            if($checkTransactionItemDelete->count() > 0){
                foreach($checkTransactionItemDelete as $deletedItem){
                    $deletedItem->delete();
                };
            }

            // Get Last Audit on Transaction
            if($data->wasChanged()){
                $lastAudit = $data->audits()->get()->last();
                $lastAudit->extra_type = get_class($transactionLog);
                $lastAudit->extra_id = $transactionLog->id;
                $lastAudit->save();
            }
        });
        
        return redirect()->route('adm.transaction.index')->with([
            'status' => 'success',
            'message' => "Data Transaksi berhasil diperbaharui"
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
        $data = $this->transactionModel->query()
            ->select($this->transactionModel->getTable().'.*');

        return datatables()
            ->of($data->with(['customer' => function($q){
                return $q->select('id', 'uuid', 'name');
            }]))
            ->orderColumn('invoice', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->addColumn('id', function($data){
                return $data->id;
            })
            ->toJson();
    }

    /**
     * Datatable data from storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function datatableAccounting(Request $request, $id)
    {
        $transaction = $this->transactionModel->where('uuid', $id)
            ->firstOrFail();

        $data = $this->accountingModel->query()
            ->select($this->accountingModel->getTable().'.*')
            ->where('transaction_id', $transaction->id);

        return datatables()
            ->of($data->with('user'))
            ->addColumn('id', function($data){
                return $data->id;
            })
            ->toJson();
    }
}
