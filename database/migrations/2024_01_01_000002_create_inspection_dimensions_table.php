<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspection_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->enum('dimension_type', ['thickness', 'width', 'length', 'diameter', 'other']);
            $table->string('specification')->nullable();
            $table->string('tolerance')->nullable();
            $table->decimal('sample_1', 10, 3)->nullable();
            $table->decimal('sample_2', 10, 3)->nullable();
            $table->decimal('sample_3', 10, 3)->nullable();
            $table->decimal('sample_4', 10, 3)->nullable();
            $table->decimal('sample_5', 10, 3)->nullable();
            $table->decimal('sample_6', 10, 3)->nullable();
            $table->decimal('sample_7', 10, 3)->nullable();
            $table->decimal('sample_8', 10, 3)->nullable();
            $table->decimal('sample_9', 10, 3)->nullable();
            $table->decimal('sample_10', 10, 3)->nullable();
            $table->decimal('rata_rata', 10, 3)->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspection_dimensions');
    }
};