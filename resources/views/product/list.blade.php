@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Products</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
    <a href="/product/form" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
  </div>
  <div class="card-body">
    <form action="" method="get" class="row">
      <div class="form-group col">
        <input type="text" class="form-control" placeholder="Product Name" name="name" autocomplete="off" value="{{ (request()->get('name')) ? request()->get('name') : '' }}">
      </div>
      <div class="form-group col">
        <input type="text" class="form-control" placeholder="Product Code" name="code" autocomplete="off" value="{{ (request()->get('code')) ? request()->get('code') : '' }}">
      </div>
      <div class="form-group col">
        <select name="status" class="form-control">
          <option value="null">--- Status ---</option>
          <option value="active" {{ (request()->get('status') && request()->get('status') == 'active') ? 'selected' : '' }}>Active</option>
          <option value="nonactive" {{ (request()->get('status') && request()->get('status') == 'nonactive') ? 'selected' : '' }}>Nonactive</option>
        </select>
      </div>
      <div class="col text-right">
        <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>
    <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th width="100">#</th>
          <th>Name</th>
          <th>Code</th>
          <th>Category</th>
          <th>Price</th>
          <th>Purchase</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if (count($data) > 0)
          @foreach ($data as $row)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $row->name }}</td>
              <td>{{ $row->code }}</td>
              <td>{{ $row->category->category }}</td>
              <td>{{ number_format($row->price) }}</td>
              <td>{{ number_format($row->purchase_price) }}</td>
              <td>{!! ($row->status) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Non Active</span>' !!}</td>
              <td width="100">
                <a href="/product/form/{{ $row->id }}" class="btn btn-warning btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                <a href="/product/delete?id={{ $row->id }}" onclick="return confirm('Are you sure want to delete this product?')" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="8" class="text-center">No data found</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection