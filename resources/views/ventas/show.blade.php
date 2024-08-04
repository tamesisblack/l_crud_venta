@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Venta</h1>
    <p><strong>Cliente:</strong> {{ $venta->cliente_nombre }}</p>
    <p><strong>Fecha de Venta:</strong> {{ $venta->fecha_venta }}</p>
    <p><strong>Estado:</strong> {{ $venta->estado }}</p>
    <p><strong>Método de Pago:</strong> {{ $venta->metodo_pago }}</p>
    <p><strong>Total:</strong> {{ $venta->total }}</p>

    <h2>Detalles de la Venta</h2>
    <ul>
        @foreach($venta->detalles as $detalle)
            <li>
                Producto: {{ $detalle->producto_nombre }},
                Cantidad: {{ $detalle->cantidad }},
                Precio Unitario: {{ $detalle->precio_unitario }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
