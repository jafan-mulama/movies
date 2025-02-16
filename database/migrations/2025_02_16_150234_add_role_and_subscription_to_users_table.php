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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained('roles');
            $table->foreignId('subscription_plan_id')->nullable()->after('role_id')->constrained('subscription_plans');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_plan_id');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->boolean('is_active')->default(true)->after('bio');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn([
                'role_id',
                'subscription_plan_id',
                'subscription_ends_at',
                'avatar',
                'bio',
                'is_active',
                'deleted_at'
            ]);
        });
    }
};
