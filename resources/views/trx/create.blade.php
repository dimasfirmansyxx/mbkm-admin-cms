@extends('templates.main')

@section('content')
<div class="transaction-page">
  <div class="row">
    
    <div class="card col-md-4">
      <div class="card-body">
        <div class="table-cart">
          <table>
            <thead>
              <tr>
                <th></th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody id="listCart">
            </tbody>
          </table>
        </div>

        <div class="table-total">
          <table>
            <tr>
              <td>Subtotal</td>
              <td class="text-right" id="lblSubtotal">0</td>
            </tr>
            <tr>
              <td>Discount</td>
              <td class="text-right" id="lblDiscount">0</td>
            </tr>
            <tr>
              <td>Grand Total</td>
              <td class="text-right" id="lblGrandTotal">0</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="product-list">
        
        <div class="category-section">
          <button class="btn btn-dark btn-sm btnSelectCategory" data-category="all">All</button>
          @foreach ($categories as $category)
            <button class="btn btn-secondary btn-sm btnSelectCategory" data-category="{{ $category->id }}">{{ $category->category }}</button>
          @endforeach
        </div>

        <div class="product-section row mt-2 ml-1">
          @foreach ($products as $product)
            <div class="product-item card col-2 m-1 categoryOf{{$product->category->id}}" data-product="{{ $product->id }}">
              <div class="card-body text-center">
                <b>{{ $product->name }}</b> <br>
                <small>{{ $product->category->category }}</small>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>

  </div>
</div>

<div class="floating-button">
  <button class="btn btn-danger btn-sm" id="btnCancel"><i class="fas fa-times"></i> Cancel</button>
  <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#mdlVoucher"><i class="fas fa-ticket-alt"></i> Voucher</button>
  <button class="btn btn-primary btn-sm" id="btnSaveTransaction"><i class="fas fa-save"></i> Save</button>
  <button class="btn btn-success btn-sm" id="btnTransactionDone"><i class="fas fa-check"></i> Transaction Done</button>
</div>

<div class="modal fade" id="mdlSelectProduct" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mdlSelectProductTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmSelectProduct" class="row">
          <div class="form-group col-12">
            <label>Product</label>
            <input type="text" class="form-control" disabled id="txtProductName">
          </div>
          <div class="form-group col-md-6">
            <label>Price</label>
            <input type="text" class="form-control" disabled id="txtProductPrice">
          </div>
          <div class="form-group col-md-6">
            <label>Qty <span class="text-danger">*</span></label>
            <input type="number" required class="form-control" autocomplete="off" id="txtProductQty">
          </div>
          <div class="form-group col-12">
            <label>Subtotal</label>
            <input type="text" class="form-control" disabled id="txtProductSubtotal">
          </div>
          <div class="col-12 text-right">
            <button class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mdlVoucher" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Voucher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmVoucher" class="row">
          <div class="form-group col-12">
            <label>Code <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required autocomplete="off" id="txtVoucherCode">
          </div>
          <div class="col-12 text-right">
            <button class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mdlCustomer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmCustomer" class="row">
          <div class="form-group col-12">
            <label>Customer Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required autocomplete="off" id="txtCustName">
          </div>
          <div class="form-group col-6">
            <label>Customer Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" required autocomplete="off" id="txtCustEmail">
          </div>
          <div class="form-group col-6">
            <label>Customer Phone</label>
            <input type="tel" class="form-control" autocomplete="off" id="txtCustPhone">
          </div>
          <div class="form-group col-12">
            <label>Additional Request</label>
            <textarea class="form-control" id="txtCustRequest"></textarea>
          </div>
          <div class="col-12 text-right">
            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<form action="" method="post" id="frmMaster" style="display: none">
  <textarea id="txtMaster" name="data"></textarea>
</form>

<script>
  $(function(){

    const cart = (localStorage.getItem('cart') != undefined) ? JSON.parse(localStorage.getItem('cart')) : {}
    const total = {
      subtotal: 0,
      discount: {type: 'flat', value: 0, voucher: ''},
      total: 0,
    }
    let tmpCart = {}
    let customer = {}

    let action = null

    loadCart()
    function loadCart() {
      $('#listCart').html('')
      total.subtotal = 0
      total.total = 0

      for(const index in cart) {
        const item = cart[index]
        $('#listCart').append(`
        <tr>
          <td class="text-center">
            <button class="badge badge-danger btnRemove" data-item="${item.id}"><i class="fas fa-times"></i></button>
            <button class="badge badge-warning btnUpdate" data-item="${item.id}"><i class="fas fa-pen"></i></button>
          </td>
          <td>${item.name}</td>
          <td>${parseFloat(item.price)}</td>
          <td>${item.qty}</td>
          <td>${parseFloat(item.subtotal)}</td>
        </tr>
        `)

        total.subtotal += parseFloat(item.subtotal)
      }

      let discount = total.discount.value
      if(total.discount.type == 'percentage') discount = total.subtotal * discount / 100
      total.total = total.subtotal - discount

      let discountView = parseFloat(total.discount.value)
      if(total.discount.type == 'percentage') discountView += `% (${discount})`

      $('#lblSubtotal').html(total.subtotal)
      $('#lblDiscount').html(discountView)
      $('#lblGrandTotal').html(total.total)

      localStorage.setItem('cart',JSON.stringify(cart))
    }

    $('#btnCancel').click(function(){
      if(confirm('Are you sure want to cancel this transaction ?')) {
        localStorage.removeItem('cart')
        window.location = '/trx'
      }
    })

    $('.btnSelectCategory').click(function(){
      const category = $(this).data('category')
      $('.product-item').css('display','none')
      if(category == 'all') $('.product-item').css('display','')
      else $('.categoryOf'+category).css('display','')

      $('.btnSelectCategory').removeClass('btn-dark')
      $('.btnSelectCategory').removeClass('btn-secondary')
      $('.btnSelectCategory').addClass('btn-secondary')
      $(this).addClass('btn-dark')
    })

    $('.product-item').click(function(){
      const id = $(this).data('product')
      if(cart[id] != undefined) {
        tmpCart = cart[id]
        $('#mdlSelectProduct').modal('show')
      } else {
        $.ajax({
          url: `/trx/product/${id}`,
          type: 'get',
          dataType: 'json',
          success:function(res) {
            tmpCart = res.data
            tmpCart.qty = 1
            tmpCart.subtotal = res.data.price
            $('#mdlSelectProduct').modal('show')
          },
          error: function(err) {
            alert(err.responseJSON.message)
          }
        })
      }
    })

    $('#mdlSelectProduct').on('shown.bs.modal',function(){
      $('#txtProductName').val(tmpCart.name)
      $('#txtProductPrice').val(parseFloat(tmpCart.price))
      $('#txtProductQty').val(tmpCart.qty)
      $('#txtProductSubtotal').val(parseFloat(tmpCart.subtotal))
      $('#mdlSelectProductTitle').html(tmpCart.name)
    })

    $('#txtProductQty').on('change',function(){
      tmpCart.qty = $(this).val()
      calculateByQty()
    })

    $('#txtProductQty').on('keyup',function(){
      tmpCart.qty = $(this).val()
      calculateByQty()
    })

    function calculateByQty() {
      if(tmpCart.qty != '') {
        const total = parseFloat(tmpCart.price) * parseFloat(tmpCart.qty)
        $('#txtProductSubtotal').val(total)
        tmpCart.subtotal = total
      }
    }

    $('#frmSelectProduct').on('submit',function(e){
      e.preventDefault()
      if(tmpCart.qty > 0) {
        cart[tmpCart.id] = tmpCart
        loadCart()
        $('#mdlSelectProduct').modal('hide')
      } else alert('Qty must be greater than zero')
    })

    $('#frmVoucher').on('submit',function(e){
      e.preventDefault()
      $.ajax({
        url: '/trx/voucher',
        data: { code: $('#txtVoucherCode').val() },
        type: 'post',
        dataType: 'json',
        success:function(res) {
          total.discount = res.data
          loadCart()
          $('#mdlVoucher').modal('hide')
        },
        error: function(err) {
          alert(err.responseJSON.message)
        }
      })
    })

    $('#listCart').on('click','.btnRemove',function(){
      const id = $(this).data('item')
      delete cart[id]
      loadCart()
    })

    $('#listCart').on('click','.btnUpdate',function(){
      const id = $(this).data('item')
      tmpCart = cart[id]
      $('#mdlSelectProduct').modal('show')  
    })

    $('#btnSaveTransaction').click(function(){
      action = 'save'
      $('#mdlCustomer').modal('show')
    })

    $('#frmCustomer').submit(function(e){
      e.preventDefault()
      customer = {
        name: $('#txtCustName').val(),
        email: $('#txtCustEmail').val(),
        phone: $('#txtCustPhone').val(),
        additional_request: $('#txtCustRequest').val(),
      }
      submitTransaction()
    })

    function submitTransaction() {
      if(action == 'save'){
        const items = cartReformat()
        const data = {
          items: items,
          customer: customer,
          total: total,
          action: 'save'
        }
        localStorage.removeItem('cart')
        $('#txtMaster').val(JSON.stringify(data))
        $('#frmMaster').submit()
      }

    }

    function cartReformat() {
      const data = []
      for(const index in cart) data.push(cart[index])

      return data
    }

  })
</script>
@endsection