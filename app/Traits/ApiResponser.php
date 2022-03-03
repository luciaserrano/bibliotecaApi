<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    function successResponse($data, $code = 200)
    {
         return response()->json($data, $code); 
    }
    
    function errorResponse($message, $code)
    {
        return response()->json(['error' => ['message' => $message, 'code' => $code]], $code);
    }

    function showAll($collection, $code = 200)
    {
        //Si esa coleccion esta vacia devuelvo la coleccion
        if ($collection ->isEmpty()){
            return $this->successResponse(['data' => $collection], $code);
        }

        $collection = $this->paginateCollection($collection); //La coleccion la pagino

        return $this->successResponse(['data' => $collection], $code);
    }
    
    function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse(['data' => $instance], $code);
    }

    function showMessage($message, $code = 200)
    {
        return $this->successResponse($message, $code);
    }

     //Funcion para trocear
     function paginateCollection(Collection $collection)
     {
         //Que el valor no sea un string sino un entero
         $rules = [
             'por_pagina' => 'integer|min:2|max:50',
         ];
         $message = [
            'por_pagina.min' => 'La pagina debe ser de al menos 2.',
            'por_pagina.max' => 'La pagina es como maximo 50.',
            'por_pagina.integer' => 'El valor debe ser un numero.',
        ];

         Validator::validate(request()->all(),$rules, $message); //todo lo que venga en la peticion pasale estas reglas //Clase Facade
         $page = LengthAwarePaginator::resolveCurrentPage(); //Clase Paginator
 
         $perPage = 15;
         if (request()->has('por_pagina')){
             $perPage = (int) request()->por_pagina;
         }
 
         $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();//TROZEA desde donde estoy (($page - 1) * $perPage) hasta donde quieres que coja
         
         //Ahora tenemos que paginarlo
         $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page,[
             'path' => LengthAwarePaginator::resolveCurrentPath(),
         ]);
 
         $paginated->appends(request()->all());
         return $paginated;
         
     }
}