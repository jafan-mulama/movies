<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('duration_in_days');
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default subscription plans
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic access with limited features',
                'price' => 0.00,
                'duration_in_days' => 0,
                'features' => json_encode(['Watch trailers', 'Limited free content']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Monthly',
                'slug' => 'premium-monthly',
                'description' => 'Full access to all premium content',
                'price' => 9.99,
                'duration_in_days' => 30,
                'features' => json_encode(['Full HD streaming', 'No ads', 'Download content', 'Premium content access']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Yearly',
                'slug' => 'premium-yearly',
                'description' => 'Full access with yearly discount',
                'price' => 99.99,
                'duration_in_days' => 365,
                'features' => json_encode(['Full HD streaming', 'No ads', 'Download content', 'Premium content access', '2 months free']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
