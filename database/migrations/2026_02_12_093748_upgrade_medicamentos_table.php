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
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->longText('ficha_tecnica')->nullable()->after('familia');
            $table->string('edades_recomendadas')->nullable()->after('ficha_tecnica');
            $table->string('dosis_recomendada')->nullable()->after('edades_recomendadas');
            $table->text('indicaciones_especiales')->nullable()->after('dosis_recomendada');
        });

        // Relation between Medicines and Clinical Guides/Docs
        Schema::create('medicamento_documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('documento_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Relation between Medicines (Related Medicines)
        Schema::create('medicamento_relacionado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('relacionado_id')->constrained('medicamentos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamento_relacionado');
        Schema::dropIfExists('medicamento_documento');

        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn(['ficha_tecnica', 'edades_recomendadas', 'dosis_recomendada', 'indicaciones_especiales']);
        });
    }
};
