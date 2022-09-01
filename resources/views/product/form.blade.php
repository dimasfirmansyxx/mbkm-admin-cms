@extends('templates.main')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Product</h1>

<div class="card shadow">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">
      <a href="/product/category" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a> 
      {{ isset($data) ? 'Edit' : 'Add' }} Product
    </h6>
  </div>
  <div class="card-body">
    <form action="" method="post" class="row">
      <div class="form-group col-md-6">
        <label>Name</label>
        <input type="text" class="form-control" name="name" required autocomplete="off">
      </div>
      <div class="form-group col-md-6">
        <label>Code</label>
        <input type="text" class="form-control" name="code" required autocomplete="off">
      </div>
      <div class="form-group col-md-6">
        <label>Category</label>
        <select name="product_categories_id" class="form-control">
          <option value="0">--- Select Category ---</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->category }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Status</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="true" name="status" id="cbxStatus" checked>
          <label class="form-check-label" for="cbxStatus">
            Aktif
          </label>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label>Price</label>
        <input type="number" class="form-control" required autocomplete="off" name="price">
      </div>
      <div class="form-group col-md-6">
        <label>Purchase Price</label>
        <input type="number" class="form-control" required autocomplete="off" name="purchase_price">
      </div>
      <div class="form-group text-center col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="true" name="new_product" id="cbxNew">
          <label class="form-check-label" for="cbxNew">
            New Product
          </label>
        </div>
      </div>
      <div class="form-group text-center col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="true" name="best_seller" id="cbxBest">
          <label class="form-check-label" for="cbxBest">
            Best Seller
          </label>
        </div>
      </div>
      <div class="form-group text-center col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="true" name="featured" id="cbxFeatured">
          <label class="form-check-label" for="cbxFeatured">
            Featured
          </label>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label>Short Description</label>
        <textarea name="short_description" class="form-control" rows="8"></textarea>
      </div>
      <div class="form-group col-md-6">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="8"></textarea>
      </div>
      <div class="col-12 text-right">
        <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
@endsection