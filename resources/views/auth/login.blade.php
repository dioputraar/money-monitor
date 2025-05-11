<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Moneytor</title>

    <!-- Custom fonts for this template-->
    {{-- <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Font Awesome Icon Picker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css">
    <link href="
        https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css
        "
        rel="stylesheet">

</head>

<body id="page-top">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
    
          <div class="col-xl-10 col-lg-12 col-md-9">
    
            <div class="card o-hidden border-0 shadow-lg my-5">
              <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                  <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                  <div class="col-lg-6">
                    <div class="p-5">
                      <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                      </div>
                      <form class="user" method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="form-group">
                          <input type="email" class="form-control form-control-user" value="{{ old('email') }}" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                          Login
                        </button>
                        <hr>
                        
                      </form>
                      <hr>
                      <div class="text-center">
                        <a class="small" href="{{ url('/register') }}">Create an Account!</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
    
          </div>
    
        </div>
    
      </div>

    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="https://itsjavi.com/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js"></script>


    <!-- Bootstrap core JavaScript-->
    {{-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="
                https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js
                "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js">
    </script>

    @yield('scripts')
</body>

</html>
