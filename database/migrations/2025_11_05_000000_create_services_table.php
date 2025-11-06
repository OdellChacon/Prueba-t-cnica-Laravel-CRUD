<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        // Evitar excepción si la tabla ya existe (idempotencia)
        if (! Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('provider_id');
                $table->string('name');
                $table->unsignedInteger('duration_minutes');
                $table->unsignedDecimal('price', 10, 2);
                $table->timestamps();
                $table->softDeletes();

                // índice/foreign key opcional — descomentar si la tabla providers existe y quieres la FK
                // $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            });

            // Si la tabla providers ya existe, añadir la FK; así la migración es más tolerante al orden.
            if (Schema::hasTable('providers')) {
                Schema::table('services', function (Blueprint $table) {
                    $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
                });
            }
        }
    }

    public function down()
    {
        // Usar dropIfExists evita errores si la tabla fue borrada manualmente
        Schema::dropIfExists('services');
    }
}
