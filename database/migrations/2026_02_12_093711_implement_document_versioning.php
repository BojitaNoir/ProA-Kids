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
        // 1. Create Versions Table
        Schema::create('documento_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->onDelete('cascade');
            $table->integer('version_number')->default(1);
            $table->string('archivo_path');
            $table->text('cambios_realizados')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // 2. Modify Documentos for Versioning and Soft Deletes
        Schema::table('documentos', function (Blueprint $table) {
            $table->foreignId('current_version_id')->nullable()->after('categoria_id')->constrained('documento_versiones')->onDelete('set null');
            $table->softDeletes();
        });

        // 3. Data Migration: Move existing paths to Version 1
        $docs = \DB::table('documentos')->get();
        foreach ($docs as $doc) {
            if ($doc->archivo_path) {
                $versionId = \DB::table('documento_versiones')->insertGetId([
                    'documento_id' => $doc->id,
                    'version_number' => 1,
                    'archivo_path' => $doc->archivo_path,
                    'cambios_realizados' => 'Versión inicial migrada.',
                    'created_by' => $doc->uploaded_by,
                    'created_at' => $doc->created_at,
                    'updated_at' => $doc->updated_at,
                ]);

                \DB::table('documentos')->where('id', $doc->id)->update([
                    'current_version_id' => $versionId
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
            $table->dropColumn('current_version_id');
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('documento_versiones');
    }
};
