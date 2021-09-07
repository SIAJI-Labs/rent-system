<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    private $accountingModel;

    /**
     * Instantiate a new BrandController instance.
     * 
     */
    public function __construct()
    {
        $this->accountingModel = new \App\Models\Accounting();
    }

    /**
     * Yearly Accounting
     * 
     */
    public function yearly(Request $request)
    {
        return view('content.adm.accounting.yearly');
    }

    /**
     * Monthly Accounting
     * 
     */
    public function monthly(Request $request, $year)
    {
        return view('content.adm.accounting.monthly', [
            'year' => $year
        ]);
    }

    /**
     * Daily Accounting
     * 
     */
    public function daily(Request $request, $year, $month)
    {
        return view('content.adm.accounting.daily', [
            'year' => $year,
            'month' => $month,
        ]);
    }

     /**
     * Detail Accounting
     * 
     */
    public function detail(Request $request, $year, $month, $date)
    {
        return view('content.adm.accounting.detail', [
            'year' => $year,
            'month' => $month,
            'date' => $date,
        ]);
    }

    /**
     * Datatable data format, from storage (Yearly)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function datatableYearly(Request $request)
    {
        $data = \DB::table($this->accountingModel->getTable())
            ->select(
                \DB::raw("YEAR(created_at) as year"),
                \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
            );

        return datatables()
            ->of($data->groupBy('year'))
            ->addColumn('detail', function($data){
                $detail = [];
                $stores = \App\Models\Store::orderBy('name', 'asc')->get();
                foreach($stores as $store){
                    $sum = \DB::table($this->accountingModel->getTable())
                        ->select(
                            \DB::raw("YEAR(created_at) as year"),
                            \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
                        )
                        ->where('store_id', $store->id)
                        ->where(\DB::raw('YEAR(created_at)'), $data->year)
                        ->groupBy('year')
                        ->first();

                    $detail[] = [
                        'name' => $store->name,
                        'amount' => !empty($sum) ? $sum->amount : 0,
                        'hexCode' => $store->chart_hex_color,
                        'rgb' => $store->chart_rgb_color
                    ];
                }

                return $detail;
            })
            ->toJson();
    }
    public function datatableMonthly(Request $request, $year)
    {
        $data = \DB::table($this->accountingModel->getTable())
            ->select(
                \DB::raw("MONTH(created_at) as month"),
                \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
            )
            ->where(\DB::raw("YEAR(created_at)"), $year);

        if($request->has('store_id') && $request->store_id != ''){
            $data->where('store_id', $request->store_id);
        }

        return datatables()
            ->of($data->groupBy('month'))
            ->addColumn('detail', function($data) use ($year){
                $detail = [];
                $stores = \App\Models\Store::orderBy('name', 'asc')->get();
                foreach($stores as $store){
                    $sum = \DB::table($this->accountingModel->getTable())
                        ->select(
                            \DB::raw("MONTH(created_at) as month"),
                            \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
                        )
                        ->where('store_id', $store->id)
                        ->where(\DB::raw('YEAR(created_at)'), $year)
                        ->where(\DB::raw('MONTH(created_at)'), $data->month)
                        ->groupBy('month')
                        ->first();

                    $detail[] = [
                        'name' => $store->name,
                        'amount' => !empty($sum) ? $sum->amount : 0,
                        'hexCode' => $store->chart_hex_color,
                        'rgb' => $store->chart_rgb_color
                    ];
                }

                return $detail;
            })
            ->toJson();
    }
    public function datatableDaily(Request $request, $year, $month)
    {
        $data = \DB::table($this->accountingModel->getTable())
            ->select(
                \DB::raw("DATE(created_at) as date"),
                \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
            )
            ->where(\DB::raw("YEAR(created_at)"), $year)
            ->where(\DB::raw("MONTH(created_at)"), $month);

        if($request->has('store_id') && $request->store_id != ''){
            $data->where('store_id', $request->store_id);
        }

        return datatables()
            ->of($data->groupBy('date'))
            ->addColumn('detail', function($data) use ($year, $month){
                $detail = [];
                $stores = \App\Models\Store::orderBy('name', 'asc')->get();
                foreach($stores as $store){
                    $sum = \DB::table($this->accountingModel->getTable())
                        ->select(
                            \DB::raw("DATE(created_at) as date"),
                            \DB::raw("SUM(IF(type='income', amount, -amount)) as amount")
                        )
                        ->where('store_id', $store->id)
                        ->where(\DB::raw('YEAR(created_at)'), $year)
                        ->where(\DB::raw('MONTH(created_at)'), $month)
                        ->where(\DB::raw('DATE(created_at)'), $data->date)
                        ->groupBy('date')
                        ->first();

                    $detail[] = [
                        'name' => $store->name,
                        'amount' => !empty($sum) ? $sum->amount : 0,
                        'hexCode' => $store->chart_hex_color,
                        'rgb' => $store->chart_rgb_color
                    ];
                }

                return $detail;
            })
            ->toJson();
    }
    public function datatableDetail(Request $request, $year, $month, $date)
    {
        $data = \DB::table($this->accountingModel->getTable())
            ->select(
                $this->accountingModel->getTable().'.id',
                'transactions.uuid',
                'transactions.invoice',
                \DB::raw("TIME(".$this->accountingModel->getTable().".created_at) as hour"),
                \DB::raw("IF(".$this->accountingModel->getTable().".type='income', ".$this->accountingModel->getTable().".amount, -".$this->accountingModel->getTable().".amount) as amount")
            )
            ->where(\DB::raw("YEAR(".$this->accountingModel->getTable().".created_at)"), $year)
            ->where(\DB::raw("MONTH(".$this->accountingModel->getTable().".created_at)"), $month)
            ->where(\DB::raw("DATE(".$this->accountingModel->getTable().".created_at)"), $date)
            ->join('transactions', 'transactions.id', '=', $this->accountingModel->getTable().'.transaction_id');

        if($request->has('store_id') && $request->store_id != ''){
            $data->where('store_id', $request->store_id);
        }

        return datatables()
            ->of($data)
            ->addColumn('detail', function($data) use ($year, $month, $date){
                $detail = [];
                $stores = \App\Models\Store::orderBy('name', 'asc')->get();
                foreach($stores as $store){
                    $sum = \DB::table($this->accountingModel->getTable())
                        ->select(
                            \DB::raw("HOUR(created_at) as hour"),
                            \DB::raw("(IF(type='income', amount, -amount)) as amount")
                        )
                        ->where('store_id', $store->id)
                        ->where(\DB::raw('YEAR(created_at)'), $year)
                        ->where(\DB::raw('MONTH(created_at)'), $month)
                        ->where(\DB::raw('DATE(created_at)'), $date)
                        ->where(\DB::raw('TIME(created_at)'), $data->hour)
                        ->where('id', $data->id)
                        ->first();

                    $detail[] = [
                        'name' => $store->name,
                        'amount' => !empty($sum) ? $sum->amount : 0,
                        'hexCode' => $store->chart_hex_color,
                        'rgb' => $store->chart_rgb_color
                    ];
                }

                return $detail;
            })
            ->toJson();
    }
}
