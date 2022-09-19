@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Role Permission</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Permission List</h6>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-3">
        <select name="role" class="form-control" id="cmbRole">
          <option value="0">--- Select Role ---</option>
          @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ (request()->get('role') == $role->id) ? 'selected' : '' }}>
              {{ strtoupper($role->name) }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-9 text-right">
        <form action="" method="post" id="frmMaster">
          <input type="hidden" name="data" id="txtMaster">
          <button class="btn btn-success">Save</button>
        </form>
      </div>
    </div>

    <table class="mt-2 table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th>Module</th>
          <th width="100">View</th>
          <th width="100">Add</th>
          <th width="100">Edit</th>
          <th width="100">Delete</th>
        </tr>
      </thead>
      <tbody>
        @if (count($permissions) < 1) 
          <tr>
            <td colspan="5" class="text-center">Select Role First</td>
          </tr>
        @else
          @foreach ($permissions as $permission)
            <tr>
              <td>{{ $permission->name }}</td>
              <td class="text-center">
                <input type="checkbox" class="cbxPermission" data-id="{{ $permission->id }}_view" {{ ($permission->view) ? 'checked' : '' }}>
              </td>
              <td class="text-center">
                <input type="checkbox" class="cbxPermission" data-id="{{ $permission->id }}_add" {{ ($permission->add) ? 'checked' : '' }}>
              </td>
              <td class="text-center">
                <input type="checkbox" class="cbxPermission" data-id="{{ $permission->id }}_edit" {{ ($permission->edit) ? 'checked' : '' }}>
              </td>
              <td class="text-center">
                <input type="checkbox" class="cbxPermission" data-id="{{ $permission->id }}_delete" {{ ($permission->delete) ? 'checked' : '' }}>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>

<script>
  $(function(){

    const permissions = JSON.parse('{!! json_encode($permissions) !!}')

    $('#cmbRole').change(function(){
      const val = $(this).val()
      if (val != 0) window.location.href = `?role=${val}`
    })

    $('.cbxPermission').change(function(){
      const val = $(this).is(':checked')
      const identifier = $(this).data('id').split('_')
      const id = identifier[0]
      const type = identifier[1]
      const index = permissions.findIndex(p => p.id == id)

      permissions[index][type] = (val) ? 1 : 0
    })

    $('#frmMaster').submit(function(e){
      e.preventDefault()
      $('#txtMaster').val(JSON.stringify(permissions))
      $(this).unbind('submit').submit()
    })

  })
</script>
@endsection