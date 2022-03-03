<?php

namespace App\Http\Controllers\Usuario; // Añadir Usuario ya que lo hemos metido en una carpeta

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; // Importamos la clase Controller

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Usuario::all()); //Función del Traits que muestre todos los usuarios
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed'
        ];

        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'email.required' => 'El campo :attribute no tiene el formato adecuado.',
            'password' => 'La password es campo obligatorio.'
        ];

        $validatedData = $request->validate($rules, $messages);
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = Usuario::create($validatedData);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        return $this->showOne($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'nombre' => 'max:255',
            'email' => ['email', Rule::unique('usuarios')->ignore($usuario->id)],
            'password' => 'min:6', // si no hacemos ninguna validacion para este, debemos ponerle '' aunque sea para tenerlo disponible en la vista
        ];

        $messages = [
            'email.unique' =>'El :attribute ya existe',
            'email.email' =>'El campo :attribute no tiene el formato adecuado.',
            'password.min' =>'El campo :attribute tiene que tener un minimo de 6 carácteres',
        ];
        $validatedData = $request->validate($rules, $messages);

        if ($request->filled('password')){
            $validatedData['password'] = bcrypt($request->input('password'));
        }

        $usuario->fill($validatedData);

        if(!$usuario->isDirty()){
            return response()->json(['error'=>['code' => 422, 'message' => 'Especifica un valor diferente' ]], 422);
        }
        $usuario->save();
        return $this->showOne($usuario);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return $this->showOne($usuario);
    }
}
