<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('merk')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->date('date_of_purchase');
            $table->string('code')->nullable();
            $table->integer('total');
            $table->integer('type')->default(2);
            $table->double('price');
            $table->text('description')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('id_location');
            $table->integer('id_work_unit');
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
        Schema::dropIfExists('assets');
    }
}
