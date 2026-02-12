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
        Schema::create('registro_validacions', function (Blueprint $create) {
            $create->id();
            $create->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $create->string('patologia');
            $create->string('medicamento');
            $create->decimal('clcr', 8, 2);
            $create->string('resultado'); // EXITOSO, ALERTA, CRITICO
            $create->text('mensaje_resultado')->nullable();
            $create->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_validacions');
    }
};
