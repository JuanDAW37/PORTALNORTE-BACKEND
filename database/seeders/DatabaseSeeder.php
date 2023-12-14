<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paise;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Le digo que llame a la clase ClienteSeeder para que cree los datos de prueba que hay allí
        $this->call(ClienteSeeder::class);

        //Aquí le digo que genere 50 países aleatorios
        Paise::factory(50)->create();
    }
}
