<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('brand_name')->nullable();
            $table->text('description');
            $table->integer('price');
            $table->tinyInteger('status')->comment('0:良好、1:目立った傷や汚れなし、2:やや傷や汚れあり、3:状態が悪い');
            $table->tinyInteger('trading_status')->default(0)->comment('0:出品中、1:売却済');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
