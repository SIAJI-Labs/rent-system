@extends('layouts.adm.app', [
    'wsecond_title' => 'Staff: Ijin Akses',
    'sidebar_menu' => 'staff',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Staff: Ijin Akses',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Staff',
                'is_active' => false,
                'url' => route('adm.staff.index')
            ], [
                'title' => 'Detail Data',
                'is_active' => false,
                'url' => route('adm.staff.show', $data->uuid)
            ], [
                'title' => 'Ijin Akses',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('content')
<form class="card" method="POST" action="{{ route('adm.permission.store', $data->uuid) }}">
    @csrf

    <div class="card-header">
        <h3 class="card-title">Staff: Ijin Akses</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.staff.show', $data->uuid) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Detail Data Staff">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Staff</th>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $data->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
        </table>

        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th>Access
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-xs btn-warning" id="allow_all" onclick="checkAll();" style="display: none">Check All</button>
                            <button type="button" class="btn btn-xs btn-info" id="notallow_all" onclick="uncheckAll();" style="display: none">Un-Check All</button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $key => $item)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $item['name'])) }}</td>
                    <td>
                        <div class="row">
                            @foreach ($item['permission'] as $value)
                            <div class="col-12 col-md-3">
                                <div class="custom-control custom-checkbox mb-1">
                                    <input class="custom-control-input" type="checkbox" name="permissions[]" value="{{ $key.'-'.$value['value'] }}" id="{{ $key.'-'.$value['value'] }}" {!! $value['value'] != 'list' ? 'disabled' : "onchange=listCheck('".$key."')" !!}>
                                    <label for="{{ $key.'-'.$value['value'] }}" class="custom-control-label">{{ $value['name'] }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <div class="btn-group float-right">
            <button type="button" onclick="formReset()" class="btn btn-sm btn-danger">Reset</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- /.card-footer-->
</form>
@endsection

@section('js_inline')
<script>
    var key_list = [
        @foreach($permissions as $key => $value)
        '{{ $key }}',
        @endforeach
    ];

    var key_old = [];
    @foreach($old_permissions as $permission)
    key_old.push("{{ $permission }}");
    @endforeach

    $(document).ready(() => {
        key_list.forEach((obj) => {
            if(key_old.includes(obj+'-list')){
                $("#"+obj+'-list').prop('checked', true);
            }

            listCheck(obj);
        });

        checkboxCheck();
    });

    function listCheck(permission){
        console.log("Check Permission is running...");

        if($("#"+permission+"-list").prop('checked') === true){
            $("#"+permission+'-create').attr('disabled', false);
            $("#"+permission+'-edit').attr('disabled', false);
            $("#"+permission+'-delete').attr('disabled', false);

            if(key_old.includes(permission+'-create')){
                $("#"+permission+'-create').prop('checked', true);
            } else {
                $("#"+permission+'-create').prop('checked', false);
            }

            if(key_old.includes(permission+'-edit')){
                $("#"+permission+'-edit').prop('checked', true);
            } else {
                $("#"+permission+'-edit').prop('checked', false);
            }

            if(key_old.includes(permission+'-delete')){
                $("#"+permission+'-delete').prop('checked', true);
            } else {
                $("#"+permission+'-delete').prop('checked', false);
            }
        } else {
            $("#"+permission+'-create').prop('checked', false).attr('disabled', true);
            $("#"+permission+'-edit').prop('checked', false).attr('disabled', true);
            $("#"+permission+'-delete').prop('checked', false).attr('disabled', true);
        }
    }

    $("input[name='permissions[]']").change(() => {
        checkboxCheck();
    });
    function checkboxCheck(){
        console.log("Checkbox Check is running..");

        let avail_option = $("input[name='permissions[]']").length;
        let selected_option = $("input[name='permissions[]']:checked").length;

        console.log("Avail Option "+avail_option);
        console.log("Selected Option "+selected_option);
        if(selected_option >= avail_option){
            $("#allow_all").attr('disabled', true).hide();
            $("#notallow_all").attr('disabled', false).show();
        } else {
            $("#allow_all").attr('disabled', false).show();
            $("#notallow_all").attr('disabled', true).hide();
        }
    }
    function checkAll(){
        console.log("Check all is running...");

        $("input[name='permissions[]']").prop('disabled', false).prop('checked', true);
        checkboxCheck();
    }
    function uncheckAll(){
        console.log("Un-Check all is running...");

        $("input[name='permissions[]']").prop('checked', false);
        key_list.forEach((obj) => {
            listCheck(obj);
        });
        checkboxCheck();
    }

    // Reset Action
    $("#btn-reset").click(function(e){
        e.preventDefault();
        $("#name").val('');

        key_list.forEach(function(obj){
            $("#"+obj+"-list").prop('checked', false);
            listCheck(obj);
        });
        // checkboxCheck();
    });
</script>
@endsection