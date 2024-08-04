<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
     // Mostrar una lista de clientes
     public function index()
     {
         $clientes = Cliente::all(); // Puedes usar paginación si hay muchos clientes
         return view('clientes.index', compact('clientes'));
     }
 
     // Mostrar el formulario para crear un nuevo cliente
     public function create()
     {
         return view('clientes.create');
     }
 
     // Almacenar un nuevo cliente
     public function store(Request $request)
     {
         $request->validate([
             'nombre' => 'required|string|max:255',
             'apellido' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:clientes',
             'telefono' => 'nullable|string|max:20',
             'direccion' => 'nullable|string|max:255',
             'ciudad' => 'nullable|string|max:100',
             'pais' => 'nullable|string|max:100',
             'estado' => 'required|boolean',
         ]);
 
         Cliente::create($request->all());
 
         return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
     }
 
     // Mostrar un cliente específico
     public function show(Cliente $cliente)
     {
         return view('clientes.show', compact('cliente'));
     }
 
     // Mostrar el formulario para editar un cliente
     public function edit(Cliente $cliente)
     {
         return view('clientes.edit', compact('cliente'));
     }
 
     // Actualizar un cliente específico
     public function update(Request $request, Cliente $cliente)
     {
         $request->validate([
             'nombre' => 'required|string|max:255',
             'apellido' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:clientes,email,' . $cliente->id,
             'telefono' => 'nullable|string|max:20',
             'direccion' => 'nullable|string|max:255',
             'ciudad' => 'nullable|string|max:100',
             'pais' => 'nullable|string|max:100',
             'estado' => 'required|boolean',
         ]);
 
         $cliente->update($request->all());
 
         return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
     }
 
     // Eliminar un cliente específico
     public function destroy(Cliente $cliente)
     {
         $cliente->delete();
 
         return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con éxito.');
     }
}
