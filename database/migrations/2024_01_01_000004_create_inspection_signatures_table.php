<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspection_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['inspector', 'checker', 'approver']);
            $table->longText('signature_data'); // Base64 signature image
            $table->string('user_name');
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspection_signatures');
    }
};