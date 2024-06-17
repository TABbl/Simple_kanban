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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->tinyText('title');
            $table->mediumText('body')->nullable();
            #todo в дальнейшем у 'status' убрать nullable (думаю так несовсем правильно делать)
            $table->string('status')->nullable();#enum('status', ['Нужно сделать','В работе','Готово к сдаче']);
            $table->dateTime('finish_at')->nullable();
            $table->timestamps(); #добавит created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
