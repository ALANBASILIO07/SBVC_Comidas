<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Muestra el formulario de registro de cliente
     */
    public function create()
    {
        // Verificar si el usuario ya tiene un cliente registrado
        $clienteExistente = Cliente::where('user_id', Auth::id())->first();
        
        if ($clienteExistente) {
            return redirect()->route('dashboard')
                ->with('info', 'Ya has completado tu registro de cliente.');
        }

        return view('clientes.complete_profile');
    }

    /**
     * Guarda el nuevo cliente en la base de datos
     */
    public function store(Request $request)
    {
        // Verificar que el usuario NO tenga ya un cliente
        $clienteExistente = Cliente::where('user_id', Auth::id())->first();
        
        if ($clienteExistente) {
            return redirect()->route('dashboard')
                ->with('warning', 'Ya tienes un registro de cliente completado.');
        }

        // Validar los datos del formulario con reglas más estrictas
        $validated = $request->validate([
            'nombre_titular' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
            ],
            'telefono' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[0-9]+$/'
            ],
            'rfc_titular' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/'
            ],
            'razon_social_titular' => 'nullable|string|max:255',
        ], [
            'nombre_titular.required' => 'El nombre completo es obligatorio',
            'nombre_titular.regex' => 'El nombre solo puede contener letras y espacios',
            'nombre_titular.min' => 'El nombre debe tener al menos 3 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono solo puede contener números',
            'telefono.min' => 'El teléfono debe tener al menos 10 dígitos',
            'telefono.max' => 'El teléfono no puede exceder 20 dígitos',
            'rfc_titular.size' => 'El RFC debe tener exactamente 13 caracteres',
            'rfc_titular.regex' => 'El formato del RFC no es válido (Ej: XAXX010101000)',
        ]);

        // Log de datos antes de crear
        \Log::info('Intentando crear cliente con datos:', [
            'user_id' => Auth::id(),
            'nombre_titular' => $validated['nombre_titular'],
            'email_contacto' => Auth::user()->email,
            'telefono' => $validated['telefono'],
            'plan' => 'estandar',
            'rfc_titular' => $validated['rfc_titular'] ?? null,
            'razon_social_titular' => $validated['razon_social_titular'] ?? null,
        ]);

        // Crear el cliente
        try {
            $cliente = Cliente::create([
                'user_id' => Auth::id(),
                'nombre_titular' => $validated['nombre_titular'],
                'email_contacto' => Auth::user()->email,
                'telefono' => $validated['telefono'],
                'plan' => 'estandar', // ✅ Plan inicial ESTÁNDAR
                'fecha_inicio_suscripcion' => now(),
                'fecha_fin_suscripcion' => null,
                'suscripcion_activa' => true,
                'rfc_titular' => $validated['rfc_titular'] ?? null,
                'razon_social_titular' => $validated['razon_social_titular'] ?? null,
            ]);

            \Log::info('Cliente creado exitosamente con ID: ' . $cliente->id);

            return redirect()->route('dashboard')
                ->with('success', '¡Registro completado exitosamente! Ahora puedes comenzar a usar todas las funciones de la plataforma.');

        } catch (\Exception $e) {
            \Log::error('Error al crear cliente:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar tu información. Error: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el perfil del cliente
     */
    public function show()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('clientes.create')
                ->with('warning', 'Primero debes completar tu registro.');
        }

        return view('clientes.show', compact('cliente'));
    }
}