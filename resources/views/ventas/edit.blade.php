@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Venta #{{ $venta->id }}</h1>
    <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="cliente_id">Cliente</label>
            <select id="cliente_id" name="cliente_id" class="form-control" required>
                <option value="">Seleccionar Cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $venta->cliente_id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_venta">Fecha de Venta</label>
            <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" value="{{ $venta->fecha_venta->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <input type="text" id="estado" name="estado" class="form-control" value="{{ $venta->estado }}" required>
        </div>

        <div class="form-group">
            <label for="metodo_pago">Método de Pago</label>
            <input type="text" id="metodo_pago" name="metodo_pago" class="form-control" value="{{ $venta->metodo_pago }}" required>
        </div>

        <div class="form-group">
            <label for="productos">Productos</label>
            <div id="productos">
                @foreach($venta->detalles as $index => $detalle)
                    <div class="form-row">
                        <div class="col">
                            <select name="productos[{{ $index }}][id]" class="form-control" required>
                                <option value="">Seleccionar Producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ $producto->id == $detalle->producto_id ? 'selected' : '' }}>
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input type="number" name="productos[{{ $index }}][cantidad]" class="form-control" value="{{ $detalle->cantidad }}" placeholder="Cantidad" required>
                        </div>
                        <div class="col">
                            <input type="number" name="productos[{{ $index }}][precio_unitario]" class="form-control" value="{{ $detalle->precio_unitario }}" placeholder="Precio Unitario" step="0.01" required>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger remove-product">Eliminar</button>
                        </div>
                    </div>
                @endforeach
                <div id="new-products"></div>
                <button type="button" class="btn btn-secondary add-product">Agregar Otro Producto</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Venta</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productIndex = {{ $venta->detalles->count() }};

        document.querySelector('.add-product').addEventListener('click', function() {
            const productosDiv = document.getElementById('new-products');
            const newProductRow = document.createElement('div');
            newProductRow.classList.add('form-row');
            newProductRow.innerHTML = `
                <div class="col">
                    <select name="productos[${productIndex}][id]" class="form-control" required>
                        <option value="">Seleccionar Producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="productos[${productIndex}][cantidad]" class="form-control" placeholder="Cantidad" required>
                </div>
                <div class="col">
                    <input type="number" name="productos[${productIndex}][precio_unitario]" class="form-control" placeholder="Precio Unitario" step="0.01" required>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-danger remove-product">Eliminar</button>
                </div>
            `;
            productosDiv.appendChild(newProductRow);

            productIndex++;
        });

        document.getElementById('productos').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.parentElement.parentElement.remove();
            }
        });
    });
</script>
@endsection
