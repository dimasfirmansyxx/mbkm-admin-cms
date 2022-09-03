@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Transactions</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Transactions List</h6>
    <a href="/trx/create" class="btn btn-primary btn-sm"><i class="fas fa-cash-register"></i> Create Transaction</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th width="100">#</th>
          <th>Code</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if (count($data) > 0)
          @foreach ($data as $row)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $row->code }}</td>
              <td>{{ $row->customer_name }}</td>
              <td>
                @if ($row->status == 1)
                  <span class="badge badge-danger">PENDING</span>
                @elseif ($row->status == 2)
                  <span class="badge badge-success">DONE / PAID</span>
                @else
                  <span class="badge badge-secondary">CANCELED</span>
                @endif
              </td>
              <td>{{ number_format($row->total) }}</td>
              <td>
                
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="text-center">No data found</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection