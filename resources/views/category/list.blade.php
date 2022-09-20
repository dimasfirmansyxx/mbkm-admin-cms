@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Product Categories</h1>

<div class="card shadow">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
    <a href="/product/category/form" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr class="text-center">
          <th width="100">#</th>
          <th>Category</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if (count($data) > 0)
          @foreach ($data as $row)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $row->category }}</td>
              <td width="100">
                <a href="/product/category/form/{{ $row->id }}" class="btn btn-warning btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                <a href="#" data-id="{{ $row->id }}" class="btn btn-danger btn-sm btn-icon btnDelete"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="3" class="text-center">No data found</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="mdlDelete" tabindex="-1" aria-labelledby="mdlDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mdlDeleteLabel">Select new Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/product/category/delete" method="get">
          <input type="hidden" name="id" id="txtId">
          <div class="form-group">
            <label>Replace To <span class="text-danger">*</span></label>
            <select name="replace" class="form-control">
              @foreach ($data as $category)
                <option value="{{ $category->id }}">{{ $category->category }}</option>
              @endforeach
            </select>
          </div>
          <div class="text-right">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(function(){
    $('.btnDelete').click(function(e){
      e.preventDefault()
      $('#txtId').val($(this).data('id'))
      $('#mdlDelete').modal('show')
    })
  })
</script>
@endsection