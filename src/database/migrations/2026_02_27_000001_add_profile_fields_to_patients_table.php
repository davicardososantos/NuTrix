<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('email');
            $table->string('biological_sex')->nullable()->after('birth_date');
            $table->string('phone')->nullable()->after('biological_sex');
            $table->string('profession')->nullable()->after('phone');
            $table->text('work_routine')->nullable()->after('profession');
            $table->text('main_goal')->nullable()->after('work_routine');
            $table->string('referral_source')->nullable()->after('main_goal');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'biological_sex',
                'phone',
                'profession',
                'work_routine',
                'main_goal',
                'referral_source',
            ]);
        });
    }
};
