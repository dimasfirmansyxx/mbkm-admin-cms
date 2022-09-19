@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">User</h1>

<div class="card shadow">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">
      <a href="/authorization/user" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a> 
      Add User
    </h6>
  </div>
  <div class="card-body">
    <form action="" method="post">
      <div class="form-group">
        <label>Username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="username" required autocomplete="off">
      </div>
      <div class="form-group">
        <label>Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="password" required autocomplete="off">
      </div>
      <div class="form-group">
        <label>Role <span class="text-danger">*</span></label>
        <select name="role" class="form-control">
          <option value="0">--- Select Role ---</option>
          @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ strtoupper($role->name) }}</option>
          @endforeach
        </select>
      </div>
      <div class="text-right">
        <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
@endsection