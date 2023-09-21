<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user_responsible');
            $table->integer('id_user_approved')->nullable();
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
        Schema::dropIfExists('procurement');
    }
}
