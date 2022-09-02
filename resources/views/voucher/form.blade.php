@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Vouchers</h1>

<div class="card shadow">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">
      <a href="/voucher" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a> 
      {{ isset($data) ? 'Edit' : 'Add' }} Voucher
    </h6>
  </div>
  <div class="card-body">
    <form action="" method="post" class="row">
      <div class="form-group col-md-12">
        <label>Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control" required name="code" autocomplete="off" value="{{ isset($data) ? $data->code : '' }}">
      </div>
      <div class="form-group col-md-6">
        <label>Type <span class="text-danger">*</span></label>
        <select name="type" class="form-control">
          <option value="0">--- Select Type ---</option>
          <option value="1" {{ (isset($data) && $data->type == '1') ? 'selected' : '' }}>Flat Discount</option>
          <option value="2" {{ (isset($data) && $data->type == '2') ? 'selected' : '' }}>Percent Discount</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Discount Value <span class="text-danger">*</span></label>
        <input type="number" class="form-control" required name="disc_value" autocomplete="off" value="{{ isset($data) ? $data->disc_value : '' }}">
      </div>
      <div class="form-group col-md-6">
        <label>Start Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" required name="start_date" value="{{ isset($data) ? $data->start_date : '' }}">
      </div>
      <div class="form-group col-md-6">
        <label>End Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" required name="end_date" value="{{ isset($data) ? $data->end_date : '' }}">
      </div>
      <div class="col-12 text-right">
        <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
@endsection