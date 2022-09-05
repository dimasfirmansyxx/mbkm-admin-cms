@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Transactions</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Transactions List</h6>
    <a href="/trx/create" class="btn btn-primary btn-sm"><i class="fas fa-cash-register"></i> Create Transaction</a>
  </div>
  <div class="card-body">
    <form action="" method="get" class="row">
      <div class="form-group col">
        <input type="text" name="code" class="form-control" placeholder="Transaction ID" autocomplete="off" value="{{ request()->filled('code') ? request()->get('code') : '' }}">
      </div>
      <div class="form-group col">
        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" autocomplete="off" value="{{ request()->filled('customer_name') ? request()->get('customer_name') : '' }}">
      </div>
      <div class="form-group col">
        <input type="email" name="customer_email" class="form-control" placeholder="Customer Email" autocomplete="off" value="{{ request()->filled('customer_email') ? request()->get('customer_email') : '' }}">
      </div>
      <div class="form-group col">
        <select name="status" class="form-control">
          <option value="0">--- Select Status ---</option>
          <option value="pending" {{ (request()->filled('status') && request()->get('status') == 'pending') ? 'selected' : '' }}>Pending</option>
          <option value="paid" {{ (request()->filled('status') && request()->get('status') == 'paid') ? 'selected' : '' }}>Paid</option>
          <option value="cancel" {{ (request()->filled('status') && request()->get('status') == 'cancel') ? 'selected' : '' }}>Cancel</option>
        </select>
      </div>
      <div class="form-group col">
        <input type="text" name="date" id="dtRangePicker" class="form-control" placeholder="Date" value="{{ (request()->filled('date')) ? request()->get('date') : \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') . ' - ' . \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}">
      </div>
      <div class="col text-right">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>
    <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th width="100">#</th>
          <th>Date</th>
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
              <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
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
              <td class="text-center" width="100">
                <div class="dropdown">
                  <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown" aria-expanded="false">
                   <i class="fas fa-chevron-down"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    @if ($row->status == 1)
                      <a class="dropdown-item" href="/trx/update/{{ $row->id }}"><i class="fas fa-pen"></i> Edit</a>
                      <a class="dropdown-item" onclick="return confirm('Are you sure want to confirm this transaction as PAID ?')" href="/trx/paid?id={{$row->id}}"><i class="fas fa-check-circle"></i> Set PAID</a>
                      <a class="dropdown-item" onclick="return confirm('Are you sure want to cancel this transaction ?')" href="/trx/cancel?id={{$row->id}}"><i class="fas fa-times-circle"></i> Cancel</a>
                    @elseif ($row->status == 0)
                      <a class="dropdown-item" onclick="return confirm('Are you sure want to delete this transaction ?')" href="/trx/delete?id={{$row->id}}"><i class="fas fa-trash"></i> Delete</a>
                    @endif
                  </div>
                </div>
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