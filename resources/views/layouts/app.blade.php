<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Los Cremosos')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
</head>

<body class="container-fluid m-0 p-0 body">
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand btn-icon" href="#"><i class="fa-solid fa-ice-cream"></i> Los Cremosos</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn btn-icon mr-2 my-1" data-toggle="modal" data-target="#addProductModal"
                        title="Registrar producto"><i class="fa-solid fa-circle-plus"></i></button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-icon mr-2 my-1" data-toggle="modal" data-target="#addFaultModal"
                        title="Registrar avería"><i class="fa-solid fa-circle-exclamation"></i></button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-icon mr-2 my-1" data-toggle="modal" data-target="#addEntryModal"
                        title="Registrar entrada"><i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-icon mr-2 my-1" data-toggle="modal" data-target="#addSaleModal"
                        title="Registrar venta"><i class="fa-solid fa-cart-shopping"></i></button>
                </li>
                <li class="nav-item">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="form-row align-items-center mx-2">
                            <div class="col-auto">
                                <label for="day" class="sr-only">Mes</label>
                                <input type="date" class="form-control mb-2 my-1 search" id="day" name="day" value="{{ $day }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-icon mb-2 my-1" title="Filtrar"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <div id="app" class="mt-4">
        @yield('content')
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
</html>