<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetRepairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_repair', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user_responsible');
            $table->integer('id_user_approval')->nullable();
            $table->integer('id_status');
            $table->text('reason')->nullable();
            $table->text('reason_reject')->nullable();
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
        Schema::dropIfExists('asset_repair');
    }
}
