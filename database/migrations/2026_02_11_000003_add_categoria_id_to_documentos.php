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
        Schema::table('documentos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->after('archivo_path')->constrained('categorias')->onDelete('restrict');
        });

        // Migrate existing 'tipo' data to initial categories
        $guiaCat = \DB::table('categorias')->where('nombre', 'Guía Clínica')->first();
        $diagCat = \DB::table('categorias')->where('nombre', 'Protocolo Diagnóstico')->first();

        if ($guiaCat) {
            \DB::table('documentos')->where('tipo', 'guia')->update(['categoria_id' => $guiaCat->id]);
        }
        if ($diagCat) {
            \DB::table('documentos')->where('tipo', 'diagnostico')->update(['categoria_id' => $diagCat->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
};
