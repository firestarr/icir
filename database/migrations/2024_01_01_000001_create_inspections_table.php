<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('material')->nullable();
            $table->string('po_no')->nullable();
            $table->string('sample_size')->nullable();
            $table->string('supplier')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('instrument_used')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('nomer_instrument')->nullable();
            $table->string('ic_no')->nullable();
            $table->string('lot_size_qty')->nullable();
            $table->string('qty_good')->nullable();
            $table->string('unit')->nullable();
            $table->enum('data_outgoing', ['Ada', 'Tidak Ada'])->nullable();
            $table->string('qty_no_good')->nullable();
            $table->enum('coa_data', ['Ada', 'Tidak Ada'])->nullable();
            $table->enum('icp_data', ['Expired', 'Valid'])->nullable();
            $table->string('aql')->nullable();
            $table->enum('acc_reject', ['ACC', 'REJECT'])->nullable();
            $table->string('supplier_lot_no')->nullable();
            $table->enum('status', ['draft', 'inspected', 'checked', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspections');
    }
};