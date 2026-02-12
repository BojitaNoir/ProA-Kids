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
            $table->foreignId('subcategory_id')->nullable()->after('categoria_id')->constrained('subcategorias')->onDelete('set null');
            $table->unsignedBigInteger('group_id')->nullable()->after('uploaded_by');
            // Note: group_id is nullable and not constrained yet as per request.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn(['subcategory_id', 'group_id']);
        });
    }
};
