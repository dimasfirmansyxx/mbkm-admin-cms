@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Vouchers</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Vouchers List</h6>
    <a href="/voucher/form" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th width="100">#</th>
          <th>Code</th>
          <th>Type</th>
          <th>Discount</th>
          <th>Period</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if (count($data) > 0)
          @foreach ($data as $row)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $row->code }}</td>
              <td>{{ ($row->type == '1') ? 'Flat' : 'Percentage' }}</td>
              <td>{{ number_format($row->disc_value) }}</td>
              <td>
                {{ \Carbon\Carbon::parse($row->start_date)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($row->end_date)->format('d M Y') }}
              </td>
              <td>{!! ($row->status) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Claimed</span>' !!}</td>
              <td>
                <a href="/voucher/form/{{ $row->id }}" class="btn btn-warning btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                <a href="/voucher/delete?id={{ $row->id }}" onclick="return confirm('Are you sure want to delete this voucher ?')" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="7" class="text-center">No data found</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection