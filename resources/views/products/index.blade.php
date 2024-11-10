@extends('layouts.app')

@section('title', 'Los Cremosos')

@section('content')
    <div class="container">
        <!-- Mostrar mensajes de error -->
        @if ($errors->any())
            <div id="error-alert" class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row my-3">
            <div class="col-md-7">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="mr-3">Productos</h2>
                    <input type="text" id="search" class="form-control w-50 mx-auto" placeholder="Buscar producto...">
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm text-center">
                        <thead class="thead-dark sticky-top">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Costo</th>
                                <th>Precio</th>
                                <th>Averías</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->cost, 0, ',', '.') }}</td>
                                    <td>${{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->faults }}</td>
                                    <td>
                                        <!-- Botón para abrir el modal de edición -->
                                        <button class="btn btn-sm btn-icon" data-toggle="modal"
                                            data-target="#editProductModal{{ $item->id }}" title="Editar producto">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal de eliminación -->
                                        <button class="btn btn-sm btn-icon" data-toggle="modal"
                                            data-target="#deleteProductModal{{ $item->id }}" title="Eliminar producto">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal para editar producto -->
                                <div class="modal fade" id="editProductModal{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editProductModalLabel{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editProductModalLabel{{ $item->id }}">
                                                    Editar Producto</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('products/' . $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="editProductName{{ $item->id }}">Nombre</label>
                                                        <input type="text" class="form-control"
                                                            id="editProductName{{ $item->id }}" name="name"
                                                            value="{{ $item->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            for="editProductQuantity{{ $item->id }}">Cantidad</label>
                                                        <input type="number" class="form-control"
                                                            id="editProductQuantity{{ $item->id }}" name="quantity"
                                                            value="{{ $item->quantity }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editProductCost{{ $item->id }}">Costo</label>
                                                        <input type="number" class="form-control"
                                                            id="editProductCost{{ $item->id }}" name="cost"
                                                            value="{{ $item->cost }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editProductPrice{{ $item->id }}">Precio</label>
                                                        <input type="number" class="form-control"
                                                            id="editProductPrice{{ $item->id }}" name="price"
                                                            value="{{ $item->price }}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para eliminar producto -->
                                <div class="modal fade" id="deleteProductModal{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="deleteProductModalLabel{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteProductModalLabel{{ $item->id }}">
                                                    Eliminar Producto</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('products/' . $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p>¿Estás seguro de que deseas eliminar este producto?</p>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-5">
                <h2>Ventas</h2>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm text-center my-3">
                        <thead class="thead-dark sticky-top">
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesByDate as $sale)
                                <tr>
                                    <td>{{ $sale->date }}</td>
                                    <td>{{ $sale->product_name }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>${{ number_format($sale->total_sale, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Registrar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="productName">Nombre del Producto</label>
                            <input type="text" class="form-control" id="productName" name="name" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="productQuantity">Cantidad</label>
                            <input type="number" class="form-control" id="productQuantity" name="quantity" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="productCost">Costo</label>
                            <input type="number" class="form-control" id="productCost" name="cost" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Precio</label>
                            <input type="number" class="form-control" id="productPrice" name="price" required
                                autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar avería -->
    <div class="modal fade" id="addFaultModal" tabindex="-1" role="dialog" aria-labelledby="addFaultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFaultModalLabel">Registrar Avería</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addFaultForm" method="POST" action="{{ route('products.addFault') }}">
                        @csrf
                        <div class="form-group">
                            <label for="faultProduct">Producto</label>
                            <select class="form-control" id="faultProduct" name="product_id" required>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="faultQuantity">Cantidad</label>
                            <input type="number" class="form-control" id="faultQuantity" name="quantity"
                                min="0" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar entrada -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEntryModalLabel">Registrar Entrada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEntryForm" method="POST" action="{{ route('products.addEntry') }}">
                        @csrf
                        <div class="form-group">
                            <label for="entryProduct">Producto</label>
                            <select class="form-control" id="entryProduct" name="product_id" required>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="entryQuantity">Cantidad</label>
                            <input type="number" class="form-control" id="entryQuantity" name="quantity" required
                                autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar venta -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSaleModalLabel">Registrar Venta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSaleForm" method="POST" action="{{ route('products.registerSale') }}">
                        @csrf
                        <div class="form-group">
                            <label for="saleProductSearch">Buscar Producto</label>
                            <input type="text" class="form-control" id="saleProductSearch" placeholder="Buscar producto...">
                            <!-- Lista de productos filtrados -->
                            <ul id="productList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto; display: none;">
                                @foreach ($items as $item)
                                    <li class="list-group-item product-option" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                        {{ $item->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" id="saleProduct" name="product_id">
                        <div class="form-group">
                            <label for="saleQuantity">Cantidad</label>
                            <input type="number" class="form-control" id="saleQuantity" name="quantity" min="0" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="col-md-12 d-flex justify content-betwen">
            @foreach ($salesData as $sales)
                <div class="p-2">
                    <h6>Venta total del dia: ${{ number_format($sales->total_sales, 0, ',', '.') }}</h6>
                </div>
                <div class="p-2 mx-4">
                    <h6>Utilidad total del dia: ${{ number_format($sales->total_profit, 0, ',', '.') }}</h6>
                </div>
            @endforeach
        </div>
    </footer>
    @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
