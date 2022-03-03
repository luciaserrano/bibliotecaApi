<?php

namespace App\Http\Controllers;

use App\Libro;
use App\Usuario;
use Illuminate\Http\Request;

class UsuarioLibroController extends Controller
{
    public function store(Request $request, Usuario $usuario)
    {
        $rules = [
            'libro_id' => 'required|integer',
        ];

        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'integer' => 'La id es requerida.',
        ];

        $validatedData = $request->validate($rules, $messages);
        $usuario->libros()->syncWithoutDetaching($validatedData);
        return $this->showOne($usuario);
    }

    public function destroy(Usuario $usuario, Libro $libro){
        $usuario->libros()->detach($libro->id_libro);
        return $this->showOne($usuario);
    }
}
