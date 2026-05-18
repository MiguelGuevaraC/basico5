<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['marca_id']);
            
            $table->foreignId('categoria_id')->nullable()->change();
            $table->foreignId('marca_id')->nullable()->change();
            
            $table->foreign('categoria_id')->references('id')->on('categorias')->nullOnDelete();
            $table->foreign('marca_id')->references('id')->on('marcas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['marca_id']);
            
            $table->foreignId('categoria_id')->nullable(false)->change();
            $table->foreignId('marca_id')->nullable(false)->change();
            
            $table->foreign('categoria_id')->references('id')->on('categorias')->cascadeOnDelete();
            $table->foreign('marca_id')->references('id')->on('marcas')->cascadeOnDelete();
        });
    }
};
