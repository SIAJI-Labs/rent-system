<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $acl = 'customer';
    private $customerModel;
    private $customerContactModel;
    private $customerMortgageModel;
    private $fileLocation = 'files/customer';

    /**
     * Instantiate a new CustomerController instance.
     * 
     */
    public function __construct()
    {
        $this->customerModel = new \App\Models\Customer();
        $this->customerContactModel = new \App\Models\CustomerContact();
        $this->customerMortgageModel = new \App\Models\CustomerMortgage();

        // Spatie ACL
        $this->middleware('permission:'.$this->acl.'-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:'.$this->acl.'-create', ['only' => ['create','store']]);
        $this->middleware('permission:'.$this->acl.'-edit', ['only' => ['edit','update']]);
    }

    /**
     * Upload file to specific location
     * 
     * @param Request $request (File)
     * @return String $filename
     */
    private function uploadImage($file, $old_files = null)
    {
        $fullname = '';
        if(!empty($file)){
            // Insert new File/Data
            $uploadedFile = $file;
            $filename = 'customers-'.(Carbon::now()->timestamp+rand(1,1000));
            $fullname = $filename.'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filesize = $uploadedFile->getSize();
            $path = $uploadedFile->storeAs($this->fileLocation, $fullname);

            if(!empty($old_files)){
                \Storage::delete($this->fileLocation.'/'.$old_files);
            }
        }

        return $fullname;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.customer.create');
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
            'email' => ['nullable', 'email', 'max:191'],
            'identity_type' => ['required', 'string', 'max:191'],
            'identity_number' => ['required', 'string', 'unique:'.$this->customerModel->getTable().',identity_number'],
            'contact.*.type' => ['required', 'string', 'in:mobile,phone,instagram,twitter,facebook'],
            'contact.*.value' => ['required', 'string', 'max:191', 'distinct'],
            'mortgage.*.type' => ['required', 'string'],
            'mortgage.*.value' => ['nullable', 'string', 'max:191', 'distinct'],
            'mortgage.*.file' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'name.required' => 'Field Nama Kostumer wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kostumer tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kostumer melebihi batas jumlah karakter (191)!',
            'address.string' => 'Field Alamat Kostumer tidak valid!',
            'email.email' => 'Nilai pada Field Email Kostumer tidak valid!',
            'email.max' => 'Nilai pada Field Email Kostumer melebihi batas jumlah karakter (191)!',
            'identity_type.required' => 'Field Jenis Identitas wajib diisi!',
            'identity_type.string' => 'Nilai pada Field Jenis Identitas tidak valid!',
            'identity_type.max' => 'Nilai pada Field Jenis Identitas melebihi batas jumlah karakter (191)!',
            'identity_number.required' => 'Field Nomor Identitas wajib diisi!',
            'identity_number.string' => 'Nilai pada Field Nomor Identitas tidak valid!',
            'identity_number.unique' => 'Nilai pada Field Jenis Identitas sudah ada!',
            'contact.*.type.required' => 'Field Jenis Kontak wajib diisi!',
            'contact.*.type.string' => 'Field Jenis Kontak tidak valid!',
            'contact.*.type.in' => 'Field Jenis Kontak tidak tersedia!',
            'contact.*.value.required' => 'Field Informasi Kontak wajib diisi!',
            'contact.*.value.string' => 'Field Informasi Kontak tidak valid!',
            'contact.*.value.max' => 'Nilai pada Field Jenis Identitas melebihi batas jumlah karakter (191)!',
            'contact.*.value.distinct' => 'Terdapat duplikasi nilai pada Field Informasi Kontak!',
            
            'mortgage.*.type.required' => 'Field Jenis Jaminan wajib diisi!',
            'mortgage.*.type.string' => 'Field Jenis Jaminan tidak valid!',
            'mortgage.*.value.required' => 'Field Informasi Jaminan wajib diisi!',
            'mortgage.*.value.string' => 'Field Informasi Jaminan tidak valid!',
            'mortgage.*.value.max' => 'Nilai pada Field Jaminan Identitas melebihi batas jumlah karakter (191)!',
            'mortgage.*.value.distinct' => 'Terdapat duplikasi nilai pada Field Informasi Jaminan!',
            'mortgage.*.file.required' => 'Field Gambar wajib diisi!',
            'mortgage.*.file.mimes' => 'Format Gambar pada Field Gambar tidak didukung!',
            'mortgage.*.file.max' => 'Ukuran gambar pada Field Gambar melebihi batas ukuran file (1024kb / 1MB)!'
        ]);

        \DB::transaction(function () use($request){
            $data = $this->customerModel;
            $data->name = $request->name;
            $data->identity_number = $request->identity_number;
            $data->identity_type = $request->identity_type;
            $data->address = $request->address;
            $data->save();

            if($request->has('contact')){
                $contactInformationArr = [];
                foreach($request->contact as $key => $value){
                    $contactInformationArr[] = new \App\Models\CustomerContact([
                        'type' => $value['type'],
                        'value' => $value['value']
                    ]);
                }

                if(!empty($contactInformationArr)){
                    $data->customerContact()->saveMany($contactInformationArr);
                }
            }
            if($request->has('mortgage')){
                $mortgageArr = [];
                foreach($request->mortgage as $key => $value){
                    $mortgageArr[] = new \App\Models\CustomerMortgage([
                        'type' => $value['type'],
                        'value' => !empty($value['value']) ? $value['value'] : null,
                        'pict' => $this->uploadImage($value['file'])
                    ]);
                }

                if(!empty($mortgageArr)){
                    $data->customerMortgage()->saveMany($mortgageArr);
                }
            }
        });

        return redirect()->route('adm.customer.index')->with([
            'status' => 'success',
            'message' => 'Data Kostumer berhasil disimpan'
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
        $data = $this->customerModel->where('uuid', $id)
            ->firstOrFail();

        if(request()->ajax()){
            return response()->json([
                'status' => true,
                'message' => 'Data Fetched',
                'data' => $data->load(['customerContact', 'customerMortgage'])->makeVisible(['identity_number', 'identity_type'])
            ]);
        }
        return view('content.adm.customer.show', [
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
        $data = $this->customerModel->where('uuid', $id)
            ->firstOrFail();
        return view('content.adm.customer.edit', [
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
        $data = $this->customerModel->where('uuid', $id)    
            ->firstOrFail();

        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:191'],
            'identity_type' => ['required', 'string', 'max:191'],
            'identity_number' => ['required', 'string', 'unique:'.$this->customerModel->getTable().',identity_number,'.$data->id],
            'contact.*.type' => ['required', 'string', 'in:mobile,phone,instagram,twitter,facebook'],
            'contact.*.value' => ['required', 'string', 'max:191', 'distinct'],
            'mortgage.*.type' => ['required', 'string'],
            'mortgage.*.value' => ['nullable', 'string', 'max:191', 'distinct'],
            'mortgage.*.file' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'name.required' => 'Field Nama Kostumer wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kostumer tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kostumer melebihi batas jumlah karakter (191)!',
            'address.string' => 'Field Alamat Kostumer tidak valid!',
            'email.email' => 'Nilai pada Field Email Kostumer tidak valid!',
            'email.max' => 'Nilai pada Field Email Kostumer melebihi batas jumlah karakter (191)!',
            'identity_type.required' => 'Field Jenis Identitas wajib diisi!',
            'identity_type.string' => 'Nilai pada Field Jenis Identitas tidak valid!',
            'identity_type.max' => 'Nilai pada Field Jenis Identitas melebihi batas jumlah karakter (191)!',
            'identity_number.required' => 'Field Nomor Identitas wajib diisi!',
            'identity_number.string' => 'Nilai pada Field Nomor Identitas tidak valid!',
            'identity_number.unique' => 'Nilai pada Field Jenis Identitas sudah ada!',
            'contact.*.type.required' => 'Field Jenis Kontak wajib diisi!',
            'contact.*.type.string' => 'Field Jenis Kontak tidak valid!',
            'contact.*.type.in' => 'Field Jenis Kontak tidak tersedia!',
            'contact.*.value.required' => 'Field Informasi Kontak wajib diisi!',
            'contact.*.value.string' => 'Field Informasi Kontak tidak valid!',
            'contact.*.value.max' => 'Nilai pada Field Jenis Identitas melebihi batas jumlah karakter (191)!',
            'contact.*.value.distinct' => 'Terdapat duplikasi nilai pada Field Informasi Kontak!',
            
            'mortgage.*.type.required' => 'Field Jenis Jaminan wajib diisi!',
            'mortgage.*.type.string' => 'Field Jenis Jaminan tidak valid!',
            'mortgage.*.value.required' => 'Field Informasi Jaminan wajib diisi!',
            'mortgage.*.value.string' => 'Field Informasi Jaminan tidak valid!',
            'mortgage.*.value.max' => 'Nilai pada Field Jaminan Identitas melebihi batas jumlah karakter (191)!',
            'mortgage.*.value.distinct' => 'Terdapat duplikasi nilai pada Field Informasi Jaminan!',
            'mortgage.*.file.required' => 'Field Gambar wajib diisi!',
            'mortgage.*.file.mimes' => 'Format Gambar pada Field Gambar tidak didukung!',
            'mortgage.*.file.max' => 'Ukuran gambar pada Field Gambar melebihi batas ukuran file (1024kb / 1MB)!'
        ]);

        \DB::transaction(function () use($request, $data){
            $data->name = $request->name;
            $data->identity_number = $request->identity_number;
            $data->identity_type = $request->identity_type;
            $data->address = $request->address;
            $data->save();

            if($request->has('contact')){
                $contactInformationArr = [];
                $existsOnDb = [];
                foreach($request->contact as $key => $value){
                    if($value['validate']){
                        $contactInformationUpdate = $this->customerContactModel->findOrFail($value['validate']);
                        $contactInformationUpdate->type = $value['type'];
                        $contactInformationUpdate->value = $value['value'];
                        $contactInformationUpdate->save();

                        $existsOnDb[] = $contactInformationUpdate->id;
                    } else {
                        $contactInformationArr[] = new \App\Models\CustomerContact([
                            'type' => $value['type'],
                            'value' => $value['value']
                        ]);
                    }
                }

                // Add New
                if(!empty($contactInformationArr)){
                    $data->customerContact()->saveMany($contactInformationArr);
                }
                // Check if there's delete action
                $checkContactDelete = $this->customerContactModel->where('customer_id', $data->id)
                    ->whereNotIn('id', $existsOnDb)
                    ->get();
                if($checkContactDelete->count() > 0){
                    $checkContactDelete->delete();
                }
            } else {
                if($data->customerContact()->exists()){
                    $data->customerContact()->delete();
                }
            }

            if($request->has('mortgage')){
                $mortgageArr = [];
                $existsOnDb = [];
                foreach($request->mortgage as $key => $value){
                    if($value['validate']){
                        $contactMortgageUpdate = $this->customerMortgageModel->findOrFail($value['validate']);
                        $contactMortgageUpdate->type = $value['type'];
                        $contactMortgageUpdate->value = $value['value'];
                        if(isset($value['file'])){
                            $contactMortgageUpdate->pict = $this->uploadImage($value['file'], $contactMortgageUpdate->pict);
                        }
                        $contactMortgageUpdate->save();

                        $existsOnDb[] = $contactMortgageUpdate->id;
                    } else {
                        $mortgageArr[] = new \App\Models\CustomerMortgage([
                            'type' => $value['type'],
                            'value' => !empty($value['value']) ? $value['value'] : null,
                            'pict' => $this->uploadImage($value['file'])
                        ]);
                    }
                }

                if(!empty($mortgageArr)){
                    $data->customerMortgage()->saveMany($mortgageArr);
                }
                // Check if there's delete action
                $checkMortgageDelete = $this->customerMortgageModel->where('customer_id', $data->id)
                    ->whereNotIn('id', $existsOnDb)
                    ->get();
                if($checkMortgageDelete->count() > 0){
                    foreach($checkMortgageDelete as $item){
                        \Storage::delete($this->fileLocation.'/'.$item->pict);
                        $item->delete();
                    }
                }
            } else {
                if($data->customerContact()->exists()){
                    foreach($data->customerMortgage as $item){
                        \Storage::delete($this->fileLocation.'/'.$item->pict);
                        $item->delete();
                    }
                }
            }
        });

        return redirect()->route('adm.customer.index')->with([
            'status' => 'success',
            'message' => 'Data Kostumer berhasil diperbaharui'
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
        $data = $this->customerModel->query()
            ->select($this->customerModel->getTable().'.*');

        return datatables()
            ->of($data->withCount('customerContact', 'customerMortgage'))
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
        $data = $this->customerModel->query()
            ->select($this->customerModel->getTable().'.*');
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
