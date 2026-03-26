<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
{
    $clientes = Cliente::whereRaw('"es_mostrador" = false')
                       ->orderBy('nombre_completo')
                       ->get();
    return view('admin.clientes.index', compact('clientes'));
}

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento'   => 'required|in:cedula,tarjeta_identidad,pasaporte,nit',
            'numero_documento' => 'required|string|max:20|unique:clientes',
            'nombre_completo'  => 'required|string|max:150',
            'direccion'        => 'nullable|string|max:200',
            'ciudad'           => 'nullable|string|max:100',
            'telefono'         => 'nullable|string|max:20',
        ]);

        Cliente::create($request->all());

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'tipo_documento'   => 'required|in:cedula,tarjeta_identidad,pasaporte,nit',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento,' . $cliente->id,
            'nombre_completo'  => 'required|string|max:150',
            'direccion'        => 'nullable|string|max:200',
            'ciudad'           => 'nullable|string|max:100',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $cliente->update($request->all());

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->es_mostrador) {
            return redirect()->route('admin.clientes.index')
                             ->with('error', 'No se puede eliminar el cliente de mostrador.');
        }

        if ($cliente->ventas()->exists()) {
            return redirect()->route('admin.clientes.index')
                             ->with('error', 'No se puede eliminar este cliente porque ya tiene compras registradas.');
        }

        $cliente->delete();

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente eliminado correctamente.');
    }
}