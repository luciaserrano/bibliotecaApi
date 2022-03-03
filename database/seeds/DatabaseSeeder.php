<?php

use App\Libro;
use App\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints(); //Quitar las constraints
        Libro::truncate();//Borrar tablas
        Usuario::truncate();
        DB::table('libro_usuario')->truncate();

         $this->call(UsuarioSeeder::class);
         $this->call(LibroSeeder::class);

         $prestamos = 5;

         for($i=0; $i<$prestamos;$i++){
             $usuario = Usuario::all()->random();
             $libro = Libro::all()->random()->id;

             $usuario->libros()->attach($libro);
         };

         Schema::enableForeignKeyConstraints();
    }
}
