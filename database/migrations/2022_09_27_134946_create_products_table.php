<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('categories_id');
            $table->string('code_product');
            $table->string('name')->unique();
            $table->string('brand');
            $table->string('slug');
            $table->integer('purchase_price');
            $table->tinyInteger('discount')->default(0);
            $table->integer('selling_price');
            $table->integer('stock');
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}