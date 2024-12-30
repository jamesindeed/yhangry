<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 1 Bonus - Database Optimization
 * 
 * Added indexes for efficiency
 * 1. name 
 * 2. status 
 * 3. number_of_orders
 * 4. [status, number_of_orders] 
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('set_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->boolean('display_text')->default(true);
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_halal')->default(false);
            $table->boolean('is_kosher')->default(false);
            $table->boolean('is_seated')->default(false);
            $table->boolean('is_standing')->default(false);
            $table->boolean('is_canape')->default(false);
            $table->boolean('is_mixed_dietary')->default(false);
            $table->boolean('is_meal_prep')->default(false);
            $table->integer('status')->default(1)->index();
            $table->decimal('price_per_person', 8, 2);
            $table->decimal('min_spend', 8, 2);
            $table->integer('number_of_orders')->default(0)->index();
            $table->json('price_includes')->nullable();
            $table->string('highlight')->nullable();
            $table->boolean('available')->default(true);
            $table->timestamps();

            // Comp index for  ->  WHERE status = 1 ORDER BY number_of_orders DESC
            $table->index(['status', 'number_of_orders']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_menus');
    }
};
