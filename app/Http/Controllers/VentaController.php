<?php


namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
class VentaController extends Controller
{
    public function index()
    {
        // Recuperar todas las ventas con sus detalles
        $ventas = Venta::with('cliente', 'detalles.producto')->get();

        // Pasar las ventas a la vista
        return view('ventas.index', compact('ventas'));
    }
    public function show($id)
    {
        $query = DB::SELECT("SELECT  v.* , c.nombre as cliente_nombre
         FROM ventas v
         left join clientes  c on v.cliente_id = c.id
         WHERE v.id = $id"
        
        );
        $detalles = DB::SELECT("SELECT d.*, p.nombre as producto_nombre
         FROM detalle_ventas d
        left join productos p on d.producto_id = p.id
        WHERE d.venta_id = $id
        ");
        $query[0]->detalles = $detalles;
        $venta = $query[0];
        // Pasar la venta a la vista
        return view('ventas.show', compact('venta'));
    }
    public function create()
    {
        // Recuperar todos los clientes y productos
        $clientes = Cliente::all();
        $productos = Producto::all();

        // Pasar los datos a la vista
        return view('ventas.create', compact('clientes', 'productos'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'estado' => 'required|string',
            'metodo_pago' => 'required|string',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'fecha_venta' => Carbon::parse($request->fecha_venta),
            'estado' => $request->estado,
            'metodo_pago' => $request->metodo_pago,
            'total' => 0,
        ]);

        $total = 0;

        foreach ($request->productos as $producto) {
            $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
            
            $detalle = new DetalleVenta([
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal' => $subtotal, // Asegúrate de asignar subtotal
            ]);
            $venta->detalles()->save($detalle);

            $total += $subtotal;
        }

        $venta->update(['total' => $total]);

        return redirect()->route('ventas.index')->with('success', 'Venta creada con éxito.');
    }

    // public function update(Request $request, Venta $venta)
    // {
    //     $request->validate([
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'fecha_venta' => 'required|date',
    //         'estado' => 'required|string',
    //         'metodo_pago' => 'required|string',
    //         'productos' => 'required|array',
    //         'productos.*.id' => 'required|exists:productos,id',
    //         'productos.*.cantidad' => 'required|integer|min:1',
    //         'productos.*.precio_unitario' => 'required|numeric|min:0',
    //     ]);

    //     $venta->update([
    //         'cliente_id' => $request->cliente_id,
    //         'fecha_venta' => Carbon::parse($request->fecha_venta),
    //         'estado' => $request->estado,
    //         'metodo_pago' => $request->metodo_pago,
    //     ]);

    //     $total = 0;
    //     $venta->detalles()->delete();

    //     foreach ($request->productos as $producto) {
    //         $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
            
    //         $detalle = new DetalleVenta([
    //             'producto_id' => $producto['id'],
    //             'cantidad' => $producto['cantidad'],
    //             'precio_unitario' => $producto['precio_unitario'],
    //             'subtotal' => $subtotal, // Asegúrate de asignar subtotal
    //         ]);
    //         $venta->detalles()->save($detalle);

    //         $total += $subtotal;
    //     }

    //     $venta->update(['total' => $total]);

    //     return redirect()->route('ventas.index')->with('success', 'Venta actualizada con éxito.');
    // }
    public function edit($id)
    {
        // Recuperar la venta con sus detalles
        $venta = Venta::with('cliente', 'detalles.producto')->findOrFail($id);

        // Recuperar todos los clientes y productos
        $clientes = Cliente::all();
        $productos = Producto::all();

        // Pasar los datos a la vista
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'estado' => 'required|string|max:255',
            'metodo_pago' => 'required|string|max:255',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        // Actualizar la venta
        $venta = Venta::findOrFail($id);
        $venta->update([
            'cliente_id' => $request->input('cliente_id'),
            'fecha_venta' => $request->input('fecha_venta'),
            'estado' => $request->input('estado'),
            'metodo_pago' => $request->input('metodo_pago'),
        ]);

        // Eliminar los detalles de venta antiguos
        $venta->detalles()->delete();

        // Insertar los nuevos detalles de venta
        foreach ($request->input('productos') as $producto) {
            $venta->detalles()->create([
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal' => $producto['cantidad'] * $producto['precio_unitario'],
            ]);
        }

        // Redirigir al listado de ventas con un mensaje de éxito
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada con éxito.');
    }
    public function destroy($id)
    {
        // Encontrar la venta por ID y eliminarla
        $venta = Venta::findOrFail($id);
        $venta->delete();

        // Redirigir al listado de ventas con un mensaje de éxito
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada con éxito.');
    }
}
