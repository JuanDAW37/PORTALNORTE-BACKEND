<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente=new Cliente();
        $cliente->nombre="Luis";
        $cliente->dni="76406782T";
        $cliente->usuario="luis";
        $cliente->pass="5678";
    }
}
