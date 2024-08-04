@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Venta</h1>
    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="cliente_id">Cliente</label>
            <select id="cliente_id" name="cliente_id" class="form-control" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_venta">Fecha de Venta</label>
            <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" value="{{ old('fecha_venta') }}" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <input type="text" id="estado" name="estado" class="form-control" value="{{ old('estado') }}" required>
        </div>
        <div class="form-group">
            <label for="metodo_pago">Método de Pago</label>
            <input type="text" id="metodo_pago" name="metodo_pago" class="form-control" value="{{ old('metodo_pago') }}" required>
        </div>
        <div class="form-group">
            <label for="productos">Productos</label>
            <div id="productos-container">
                <div class="product-row">
                    <select name="productos[0][id]" class="form-control" required>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" required min="1">
                    <input type="number" name="productos[0][precio_unitario]" class="form-control" placeholder="Precio Unitario" required step="0.01" min="0">
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" onclick="addProductRow()">Agregar Otro Producto</button>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Venta</button>
    </form>
</div>

<script>
    let productIndex = 1;

    function addProductRow() {
        const container = document.getElementById('productos-container');
        const newRow = document.createElement('div');
        newRow.className = 'product-row';
        newRow.innerHTML = `
            <select name="productos[${productIndex}][id]" class="form-control" required>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
            <input type="number" name="productos[${productIndex}][cantidad]" class="form-control" placeholder="Cantidad" required min="1">
            <input type="number" name="productos[${productIndex}][precio_unitario]" class="form-control" placeholder="Precio Unitario" required step="0.01" min="0">
        `;
        container.appendChild(newRow);
        productIndex++;
    }
</script>
@endsection
