<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin</title>
    <link href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">

    <script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
  </head>
  <body id="page-top">
    <div id="wrapper">
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
          <div class="sidebar-brand-text mx-3">Admin</div>
        </a>
        <hr class="sidebar-divider my-0">
        @include('templates.navigation')
        <hr class="sidebar-divider d-none d-md-block">
        
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
      </ul>
      <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
          @include('templates.header')
          
          <div class="container-fluid">
            @if (\Session::get('error'))
              <div class="alert alert-danger">
                <i class="fas fa-times"></i> {!! \Session::get('error') !!}
              </div>
            @elseif(\Session::get('success'))
              <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {!! \Session::get('success') !!}
              </div>
            @elseif(\Session::get('info'))
              <div class="alert alert-primary">
                <i class="fas fa-info-circle"></i> {!! \Session::get('info') !!}
              </div>
            @endif
            
            @yield('content')
          </div>
        </div>

        <footer class="sticky-footer bg-white mt-5">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; Your Website 2020</span>
            </div>
          </div>
        </footer>

      </div>
    </div>
    
    <script src="{{asset('assets/js/sb-admin-2.min.js')}}"></script>
    <script>
      $(function(){
        $('#dtRangePicker').daterangepicker({
          locale: {
            format: 'Y-MM-DD'
          }
        })
      })
    </script>
  </body>
</html>