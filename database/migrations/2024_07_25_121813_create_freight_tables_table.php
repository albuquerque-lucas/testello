<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('freight_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained();
            $table->string('from_postcode');
            $table->string('to_postcode');
            $table->decimal('from_weight', 8, 2);
            $table->decimal('to_weight', 8, 2);
            $table->decimal('cost', 8, 2);
            $table->integer('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freight_tables');
    }
};
