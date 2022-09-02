@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Products</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
    <a href="/product/form" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
  </div>
  <div class="card-body">
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
                <a href="/product/form?id={{ $row->id }}" class="btn btn-warning btn-sm btn-icon"><i class="fas fa-pen"></i></a>
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