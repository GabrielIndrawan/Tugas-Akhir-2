<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribtions', function (Blueprint $table) {
            $table->id();

            $table->string('user_id');
            $table->foreign('user_id')->constrained('user_profiles')->onDelete('cascade');

            $table->string('product_name')->default('storage');
            $table->unsignedBigInteger('amount');
            $table->string('currency', 6)->default('idr');

            $table->string('status')->default('pending');

            $table->string('stripe_session_id')
                ->nullable()
                ->unique();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribtions');
    }
};
