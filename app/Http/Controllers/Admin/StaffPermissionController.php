<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffPermissionController extends Controller
{
    private $staffModel;
    private $permissions;

    /**
     * Instantiate a new StaffPermissionController instance.
     * 
     */
    public function __construct()
    {
        $this->staffModel = new \App\Models\Admin();
        $this->permissions = config('global.permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = $this->staffModel->where('uuid', $id)
            ->firstOrFail();
        $old_permissions = \Spatie\Permission\Models\Permission::join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_has_permissions.model_id', $data->id)
            ->get()
            ->pluck('name');

        return view('content.adm.staff.permission.index', [
            'data' => $data,
            'permissions' => $this->permissions,
            'old_permissions' => $old_permissions
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
    public function store(Request $request, $id)
    {
        $data = $this->staffModel->where('uuid', $id)
            ->firstOrFail();

        // Add Permission into Old and New Array
        $old_permission = $new_permission = array();
        foreach($data->permissions as $permissions){
            array_push($old_permission, $permissions->name);
        }
        if(!empty($request->permissions)){
            $new_permission = $request->permissions;
        }

        // Revoke Permission
        $revoke = array_diff($old_permission, $new_permission);
        foreach($revoke as $r){
            $data->revokePermissionTo($r);
        }
        // Assign Permission
        $assign = array_diff($new_permission, $old_permission);
        foreach($assign as $a){
            $data->givePermissionTo($a);
        }
        
        return redirect()->route('adm.permission.index', $data->uuid)->with([
            'status' => 'success',
            'message' => 'Berhasil memperbaharui data ijin akses untuk Staff '.$data->name
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
