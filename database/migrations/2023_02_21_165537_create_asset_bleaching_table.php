<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetBleachingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_bleaching', function (Blueprint $table) {
            $table->id();
            $table->integer('id_asset');
            $table->integer('id_responsible');
            $table->integer('id_approve')->nullable();
            $table->integer('status')->default(1);
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
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
        Schema::dropIfExists('asset_bleaching');
    }
}
