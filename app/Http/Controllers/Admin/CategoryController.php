<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryModel;
    private $fileLocation = 'images/category';

    /**
     * Instantiate a new CategoryController instance.
     * 
     */
    public function __construct()
    {
        $this->categoryModel = new \App\Models\Category();
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
            $filename = 'category-'.(Carbon::now()->timestamp+rand(1,1000));
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
        return view('content.adm.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.adm.category.create');
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
            'description' => ['nullable', 'string'],
            'pict' => ['nullable', 'mimes:jpg,jpeg,png', 'max:500']
        ], [
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
            'description.string' => 'Nilai pada Field Deskripsi tidak valid!',
            'pict.mimes' => 'Ekstensi gambar pada Field Gambar tidak didukung (jpg, jpeg, png)!',
            'pict.max' => 'Ukuran gambar pada Field Gambar melebihi batas ukuran (500kb)!'
        ]);

        // return response()->json($request->all());

        \DB::transaction(function () use ($request) {
            $pict = null;
            if($request->hasFile('pict')){
                $pict = $this->uploadImage($request->pict);
            }

            $data = $this->categoryModel;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->pict = $pict;
            $data->save();
        });

        return redirect()->route('adm.product.category.index')->with([
            'status' => 'success',
            'message' => 'Data Kategori berhasil disimpan'
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
        $data = $this->categoryModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.category.show', [
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
        $data = $this->categoryModel->where('uuid', $id)->firstOrFail();
        return view('content.adm.category.edit', [
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
            'description' => ['nullable', 'string'],
            'pict' => ['nullable', 'mimes:jpg,jpeg,png', 'max:500']
        ], [
            'name.required' => 'Field Nama Kategori wajib diisi!',
            'name.string' => 'Nilai pada Field Nama Kategori tidak valid!',
            'name.max' => 'Nilai pada Field Nama Kategori melebihi batas jumlah karakter (191)!',
            'description.string' => 'Nilai pada Field Deskripsi tidak valid!',
            'pict.mimes' => 'Ekstensi gambar pada Field Gambar tidak didukung (jpg, jpeg, png)!',
            'pict.max' => 'Ukuran gambar pada Field Gambar melebihi batas ukuran (500kb)!'
        ]);

        $data = $this->categoryModel->where('uuid', $id)->firstOrFail();
        \DB::transaction(function () use ($request, $data) {
            $pict = $data->pict;
            if($request->hasFile('pict')){
                $pict = $this->uploadImage($request->pict, $data->pict);
            }

            $data->name = $request->name;
            $data->description = $request->description;
            $data->pict = $pict;
            $data->save();
        });

        return redirect()->route('adm.product.category.index')->with([
            'status' => 'success',
            'message' => 'Data Kategori berhasil diperbaharui'
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
     * @return 
     */
    public function datatableAll(Request $request)
    {
        $data = $this->categoryModel->query()
            ->select($this->categoryModel->getTable().'.*');

        return datatables()
            ->of($data->withCount('product'))
            ->toJson();
    }

    /**
     * Select2 data format, from storage
     * 
     * @param Request $request
     * @return json
     */
    public function select2(Request $request)
    {
        $data = $this->categoryModel->query()
            ->select($this->categoryModel->getTable().'.*');
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
