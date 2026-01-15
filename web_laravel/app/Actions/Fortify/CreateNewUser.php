<?php

/**
 * Nombre del archivo        : CreateNewUser.php
 * Descripción               : Acción Fortify para validar y crear un usuario nuevo con validaciones reforzadas.
 * Fecha de creación         : 12/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 12/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.1
 * Fecha de mantenimiento    : 12/01/2026
 * Tipo de mantenimiento     : Seguridad / Validación
 * Descripción del mantenimiento: Validación de campos, sanitización y almacenamiento seguro.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // reglas: name permite letras unicode, espacios, guiones y apóstrofes
        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\'\-]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
            'password_confirmation' => ['required', 'same:password'],
            // Aseguramos que checkboxes estén presentes y sean 'accepted' (on/1/true)
            'terms' => ['accepted'],
            'privacy' => ['accepted'],
        ];

        // Mensajes personalizados en español (puedes moverlos a resources/lang si prefieres)
        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre contiene caracteres no permitidos.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.unique' => 'El correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password_confirmation.same' => 'Las contraseñas no coinciden.',
            'terms.accepted' => 'Debes aceptar los Términos y Condiciones.',
            'privacy.accepted' => 'Debes aceptar el Aviso de Privacidad.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        $validator->validate();

        // Sanitizar datos antes de persistir
        $data = Arr::only($input, ['name', 'email', 'password']);
        $data['name'] = trim(strip_tags($data['name']));
        $data['email'] = trim(strtolower(filter_var($data['email'], FILTER_SANITIZE_EMAIL)));
        $data['password'] = Hash::make($data['password']);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}