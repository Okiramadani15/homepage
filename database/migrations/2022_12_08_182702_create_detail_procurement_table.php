<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailProcurementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_procurement', function (Blueprint $table) {
            $table->id();
            $table->integer('id_procurement');
            $table->string('name');
            $table->integer('quantity');
            $table->integer('quantity_received')->default(0);
            $table->string('price');
            $table->integer('id_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_procurement');
    }
}
