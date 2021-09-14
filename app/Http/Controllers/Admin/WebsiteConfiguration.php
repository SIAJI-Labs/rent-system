<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteConfiguration extends Controller
{
    private $acl = 'website_configuration';
    private $websiteConfigurationModel;
    private $fileLocation = 'images';

    /**
     * Instantiate a new WebsiteConfiguration instance.
     * 
     */
    public function __construct()
    {
        $this->websiteConfigurationModel = new \App\Models\WebsiteConfiguration();

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
            $filename = 'webconf-'.(Carbon::now()->timestamp+rand(1,1000));
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
        $title = $this->websiteConfigurationModel->where('key', 'title')->first();
        return view('content.adm.website-configuration.index', [
            'title' => $title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'favicon' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'title.required' => 'Field Judul Website wajib diisi!',
            'title.string' => 'Nilai pada Field Judul Website tidak valid!',
            'title.max' => 'Nilai pada Field Judul Website melebihi batas karakter yang diijinkan (191)',
            'description.required' => 'Field Deskripsi wajib diisi!',
            'description.string' => 'Nilai pada Field Deskripsi tidak valid!',
            'favicon.required' => 'Field Favicon wajib diisi!',
            'favicon.mimes' => 'Format file pada Field Favicon tidak didukung (Format didukung: jpg,jpeg,png)!',
            'favicon.max' => 'Ukuran file pada Field Favicon melebihi batas ukuran file (1MB)!',
            'logo.required' => 'Field Logo wajib diisi!',
            'logo.mimes' => 'Format file pada Field Logo tidak didukung (Format didukung: jpg,jpeg,png)!',
            'logo.max' => 'Ukuran file pada Field Logo melebihi batas ukuran file (1MB)!',
        ]);

        \DB::transaction(function () use ($request) {
            // Website Title
            $title = $this->websiteConfigurationModel->updateOrCreate([
                'key' => 'title'
            ], [
                'value' => $request->title
            ]);
            // Website Description
            if($request->has('description') && $request->description != ""){
                $description = $this->websiteConfigurationModel->updateOrCreate([
                    'key' => 'description'
                ], [
                    'value' => $request->description
                ]);
            }
            // Website Favicon
            if($request->has('favicon') && $request->favicon != ""){
                $favicon = $this->websiteConfigurationModel->updateOrCreate([
                    'key' => 'favicon'
                ], [
                    'value' => $this->uploadImage($request->favicon)
                ]);
            }
            // Website Logo
            if($request->has('logo') && $request->logo != ""){
                $logo = $this->websiteConfigurationModel->updateOrCreate([
                    'key' => 'logo'
                ], [
                    'value' => $this->uploadImage($request->logo)
                ]);
            }
        });

        return redirect()->route('adm.website-configuration.index')->with([
            'status' => 'success',
            'message' => 'Data Pengaturan Website berhasil disimpan!'
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
    public function edit($id)
    {
        //
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
}
