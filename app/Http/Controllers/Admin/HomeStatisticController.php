<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeStatisticController extends Controller
{
    private $accountingModel;
    private $transactionModel;

    /**
     * Instantiate a new ProductController instance.
     * 
     */
    public function __construct()
    {
        $this->accountingModel = new \App\Models\Accounting();
        $this->transactionModel = new \App\Models\Transaction();
    }

    /**
     * JSON for Transaction statistic
     * 
     */
    public function jsonTransactionStatistic(Request $request)
    {
        $data = $this->transactionModel->query();
        if($request->has('filter_status') && $request->filter_status != '' && $request->filter_status != 'all'){
            $data->where('status', $request->filter_status);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Fetched',
            'data' => $data->count()
        ]);
    }

    /**
     * JSON for Cashflow statistic
     * 
     */
    public function jsonCashflowStatistic(Request $request)
    {
        $data = \DB::table($this->accountingModel->getTable())
            ->select(
                \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
            )->where(\DB::raw('YEAR(created_at)'), date("Y"));
        if($request->has('filter_month') && $request->filter_month != '' && $request->filter_month != 'all'){
            $data->where(\DB::raw('MONTH(created_at)'), $request->filter_month);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Fetched',
            'data' => $data->first()
        ]);
    }

    /**
     * Get Booking List
     * 
     */
    public function jsonTransactionList(Request $request)
    {
        $last_page = null;
        $length = 10;
        if($request->has('length')){
            $length = $request->length;
        }

        $data = $this->transactionModel->query()
            ->with(['customer' => function($d){
                return $d->select('id', 'uuid', 'name');
            }, 'transactionItem' => function($d){
                return $d->select('transaction_id', 'uuid');
            }])
            ->select($this->transactionModel->getTable().'.*')
            ->where('status', ($request->status ?? 'booking'));

        if($request->has('page')){
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate($length)->lastPage();
        }
        $data->orderBy((($request->status ?? 'booking') == 'booking' ? 'start_date' : 'end_date'), 'asc');

        return response()->json([
            'message' => 'Data Fetched',
            'data' => $data->get(),
            'extra_data' => [
                'last_page' => $last_page,
            ]
        ]);
    }
}
