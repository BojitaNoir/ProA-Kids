<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $row) {
            $row->id();
            $row->string('nombre')->unique();
            $row->timestamps();
        });

        // Insert default categories to maintain compatibility
        \DB::table('categorias')->insert([
            ['nombre' => 'Guía Clínica', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Protocolo Diagnóstico', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
