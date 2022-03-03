<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'titulo', 'descripcion',
    ];

    //Relación entre las clases 
    //belongsToMany --> Muchos a muchos
    //withTimestamps --> Porque en la tabla pivote hay un Timestamps
    public function usuarios(){
        return $this->belongsToMany(Usuario::class)->withTimestamps();
    }
}
