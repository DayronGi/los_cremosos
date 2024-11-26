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
                    <input type="text" id="search" class="form-control w-50 mx-auto search"
                        placeholder="Buscar producto...">
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm">
                        <thead class="thead sticky-top">
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Costo</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Averías</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @foreach ($items as $item)
                                <tr>
                                    <td hidden>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">${{ number_format($item->cost, 0, ',', '.') }}</td>
                                    <td class="text-center">${{ number_format($item->price, 0, ',', '.') }}</td>
                                    @php
                                        $faultCount = $faults->firstWhere('product_id', $item->id)->total_faults ?? 0;
                                    @endphp
                                    <td class="text-center">{{ $faultCount }}</td>
                                    <td class="text-center">

                                        <!-- Botón para abrir el modal de edición -->
                                        <button class="btn btn-sm btn-icon" data-toggle="modal"
                                            data-target="#editProductModal{{ $item->id }}" title="Editar producto">
                                            <i class="fa-solid fa-pen-to-square btn-edit"></i>
                                        </button>
                        
                                        <!-- Botón para abrir el modal de eliminación -->
                                        <button class="btn btn-sm btn-icon" data-toggle="modal"
                                            data-target="#deleteProductModal{{ $item->id }}" title="Eliminar producto">
                                            <i class="fa-solid fa-trash btn-delete"></i>
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
                                                <h5 class="modal-title" id="editProductModalLabel{{ $item->id }}">Editar producto</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('products/' . $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-inline-flex flex-row gap-2">
                                                        <div class="input-group my-2">
                                                            <label for="editProductName{{ $item->id }}" class="input-group-text"><i class="fas fa-tag"></i></label>
                                                            <input type="text" class="form-control p-0 px-2" id="editProductName{{ $item->id }}" name="name" value="{{ $item->name }}" placeholder="Nombre..." autocomplete="off" required>
                                                        </div>
                                                    </div>

                                                    <div class="d-inline-flex flex-row gap-2">
                                                        <div class="input-group my-2">
                                                            <label for="editProductQuantity{{ $item->id }}" class="input-group-text"><i class="fas fa-hashtag"></i></label>
                                                            <input class="form-control p-0 px-2" id="editProductQuantity{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="d-inline-flex flex-row gap-2">
                                                        <div class="input-group my-2">
                                                            <label for="editProductCost{{ $item->id }}" class="input-group-text"><i class="fas fa-coins"></i></label>
                                                            <input type="number" class="form-control" id="editProductCost{{ $item->id }}" name="cost" value="{{ $item->cost }}" placeholder="Costo..." autocomplete="off" required>
                                                        </div>
                                                    </div>

                                                    <div class="d-inline-flex flex-row gap-2">
                                                        <div class="input-group my-2">
                                                            <label for="editProductPrice{{ $item->id }}" class="input-group-text"><i class="fas fa-dollar-sign"></i></label>
                                                            <input type="number" class="form-control" id="editProductPrice{{ $item->id }}" name="price" value="{{ $item->price }}" placeholder="Precio..." autocomplete="off" required>
                                                        </div>
                                                    </div>

                                                    <br>

                                                    <button type="submit" class="btn btn-primary my-2 btn-success"><i class="fas fa-save"></i> Guardar</button>
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
                                                <h5 class="modal-title" id="deleteProductModalLabel{{ $item->id }}">Eliminar producto</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('products/' . $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p>¿Estás seguro de que deseas eliminar este producto?</p>
                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
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
                    <table class="table table-hover table-sm my-3">
                        <thead class="thead sticky-top">
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesByDate as $sale)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y') }}</td>
                                    <td>{{ $sale->product_name }}</td>
                                    <td class="text-center">{{ $sale->quantity }}</td>
                                    <td class="text-center">${{ number_format($sale->total_sale, 0, ',', '.') }}</td>
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
                    <h5 class="modal-title" id="addProductModalLabel">Registrar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="productName" class="input-group-text"><i class="fas fa-tag"></i></label>
                                <input type="text" class="form-control p-0 px-2" id="productName" name="name" placeholder="Nombre..." required autocomplete="off">
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="productQuantity" class="input-group-text"><i class="fas fa-hashtag"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="productQuantity" name="quantity" placeholder="Cantidad..." min="1" required autocomplete="off">
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="productCost" class="input-group-text"><i class="fas fa-coins"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="productCost" name="cost" placeholder="Costo..." required autocomplete="off">
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="productPrice" class="input-group-text"><i class="fas fa-dollar-sign"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="productPrice" name="price" placeholder="Precio..." required autocomplete="off">
                            </div>
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary my-2 btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar avería -->
    <div class="modal fade" id="addFaultModal" tabindex="-1" role="dialog" aria-labelledby="addFaultModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFaultModalLabel">Registrar avería</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="addFaultForm" method="POST" action="{{ route('products.addFault') }}">
                        @csrf
                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="faultProductSearch" class="input-group-text"><i class="fas fa-tag"></i></label>
                                <input type="text" class="form-control p-0 px-2" id="faultProductSearch" placeholder="Buscar producto...">
                                <ul id="faultProductList" class="productList list-group mt-2">
                                    @foreach ($items as $item)
                                    <li class="list-group-item product-option" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                        {{ $item->name }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <input type="hidden" id="faultProduct" name="product_id">
                                <label for="faultQuantity" class="input-group-text"><i class="fas fa-hashtag"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="faultQuantity" name="quantity" min="1" placeholder="Cantidad..." required autocomplete="off">
                            </div>
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary my-2 btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar entrada -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEntryModalLabel">Registrar entrada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="addEntryForm" method="POST" action="{{ route('products.addEntry') }}">
                        @csrf
                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="entryProductSearch" class="input-group-text"><i class="fas fa-tag"></i></label>
                                <input type="text" class="form-control p-0 px-2" id="entryProductSearch" placeholder="Buscar producto...">
                                <ul id="entryProductList" class="productList list-group mt-2">
                                    @foreach ($items as $item)
                                        <li class="list-group-item product-option" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                            {{ $item->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <input type="hidden" id="entryProduct" name="product_id">
                                <label for="entryQuantity" class="input-group-text"><i class="fas fa-hashtag"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="entryQuantity" name="quantity" placeholder="Cantidad..." min="1" required autocomplete="off">
                            </div>
                        </div>
                            
                        <br>

                        <button type="submit" class="btn btn-primary my-2 btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar venta -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSaleModalLabel">Registrar venta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSaleForm" method="POST" action="{{ route('products.registerSale') }}">
                        @csrf
                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <label for="saleProductSearch" class="input-group-text"><i class="fas fa-tag"></i></label>
                                <input type="text" class="form-control p-0 px-2" id="saleProductSearch" placeholder="Buscar producto...">
                                <ul id="saleProductList" class="productList list-group mt-2">
                                    @foreach ($items as $item)
                                    <li class="list-group-item product-option" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                        {{ $item->name }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="d-inline-flex flex-row gap-2">
                            <div class="input-group my-2">
                                <input type="hidden" id="saleProduct" name="product_id">
                                <label for="saleQuantity" class="input-group-text"><i class="fas fa-hashtag"></i></label>
                                <input type="number" class="form-control p-0 px-2" id="saleQuantity" name="quantity" min="0" placeholder="Cantidad..." required autocomplete="off">
                            </div>
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary my-2 btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="col-md-12 d-flex justify content-betwen">
            @foreach ($salesData as $sales)
                <div class="p-2">
                    <h6 class="my-2">Venta total del dia: ${{ number_format($sales->total_sales, 0, ',', '.') }}</h6>
                </div>
                <div class="p-2 mx-4">
                    <h6 class="my-2">Utilidad total del dia: ${{ number_format($sales->total_profit, 0, ',', '.') }}
                    </h6>
                </div>
            @endforeach
        </div>
    </footer>
    @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
