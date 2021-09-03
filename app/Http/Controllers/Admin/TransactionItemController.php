<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionItemController extends Controller
{
    private $transactionModel;
    private $transactionItemModel;

    /**
     * Instantiate a new TransactionController instance.
     * 
     */
    public function __construct()
    {
        $this->transactionModel = new \App\Models\Transaction();
        $this->transactionItemModel = new \App\Models\TransactionItem();
    }

    /**
     * JSON
     * 
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jsonIndex(Request $request, $transactionId)
    {
        $transaction = $this->transactionModel->where('uuid', $transactionId)
            ->firstOrFail();
        $item = $this->transactionItemModel->with('product', 'productDetail')->where('transaction_id', $transaction->id)
            ->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $item
        ]);
    }
    public function jsonShow(Request $request, $transactionId, $id)
    {
        $transaction = $this->transactionModel->where('uuid', $transactionId)
            ->firstOrFail();
        $item = $this->transactionItemModel->with('product', 'productDetail')->where('uuid', $id)
            ->where('transaction_id', $transaction->id)
            ->firstOrFail();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $item
        ]);
    }

    /**
     * Datatable data from storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function datatableAll(Request $request, $id)
    {
        $transaction = $this->transactionModel->where('uuid', $id)
            ->firstOrFail();

        $data = $this->transactionItemModel->query()
            ->select($this->transactionItemModel->getTable().'.*')
            ->where('transaction_id', $transaction->id);

        return datatables()
            ->of($data->with(['product', 'productDetail']))
            ->orderColumn('invoice', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->addColumn('id', function($data){
                return $data->id;
            })
            ->toJson();
    }
}
