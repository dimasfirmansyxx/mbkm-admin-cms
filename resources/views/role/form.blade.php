@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Role</h1>

<div class="card shadow">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">
      <a href="/authorization/role" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a> 
      {{ isset($data) ? 'Edit' : 'Add' }} Role
    </h6>
  </div>
  <div class="card-body">
    <form action="" method="post">
      <div class="form-group">
        <label>Role Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" required name="name" autocomplete="off" value="{{ (isset($data)) ? $data->name : '' }}">
      </div>
      <div class="text-right">
        <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
@endsection