<li class="nav-item">
  <a class="nav-link" href="/">
    <i class="fas fa-fw fa-tachometer-alt"></i>
    <span>Dashboard</span>
  </a>
</li>
<hr class="sidebar-divider">
@if (permission(auth()->user(),'transaction','view'))
  <li class="nav-item">
    <a class="nav-link" href="/trx">
      <i class="fas fa-fw fa-cash-register"></i>
      <span>Transaction</span>
    </a>
  </li>
@endif
@if (permission(auth()->user(),'product','view') || permission(auth()->user(),'product_category','view'))
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
      aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-box"></i>
    <span>Product</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @if (permission(auth()->user(),'product','view'))
          <a class="collapse-item" href="/product">Products</a>
        @endif
        @if (permission(auth()->user(),'product_category','view'))
          <a class="collapse-item" href="/product/category">Categories</a>
        @endif
      </div>
    </div>
  </li>
@endif
@if (permission(auth()->user(),'voucher','view'))
  <li class="nav-item">
    <a class="nav-link" href="/voucher">
      <i class="fas fa-fw fa-ticket-alt"></i>
      <span>Voucher</span>
    </a>
  </li>
@endif
@if (permission(auth()->user(),'user','view') || permission(auth()->user(),'role','view') || permission(auth()->user(),'authorization','view'))
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
      aria-expanded="true" aria-controls="collapseThree">
    <i class="fas fa-fw fa-lock"></i>
    <span>Authorization</span>
    </a>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        @if (permission(auth()->user(),'user','view'))
          <a class="collapse-item" href="/authorization/user">User</a>
        @endif
        @if (permission(auth()->user(),'role','view'))
          <a class="collapse-item" href="/authorization/role">Role</a>
        @endif
        @if (permission(auth()->user(),'authorization','view'))
          <a class="collapse-item" href="/authorization/permission">Permission</a>
        @endif
      </div>
    </div>
  </li>
@endif