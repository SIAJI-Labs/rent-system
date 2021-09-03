<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('invoice');
            $table->dateTime('date');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('must_end_date')->nullable();
            $table->dateTime('back_date')->nullable();
            $table->double('amount')->default(0);
            $table->double('discount')->default(0);
            $table->double('paid')->default(0);
            $table->double('charge')->default(0);
            $table->double('extra')->default(0);
            $table->enum('status', ['booking', 'process', 'complete', 'cancel'])->default('process');
            $table->longText('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};