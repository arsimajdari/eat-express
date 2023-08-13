<?php

use App\Models\Order;
use App\Models\User;
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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->nullOnDelete();
            $table->foreignIdFor(Order::class)->nullable()->nullOnDelete();
            $table->string('number')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone');
            $table->string('city');
            $table->string('zip');
            $table->string('address');
            $table->string('country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
