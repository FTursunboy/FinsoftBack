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
        Schema::table('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('INN')->after('name');
            $table->foreignId('director_id')->after('INN')->constrained('employees', 'id');
            $table->foreignId('chief_accountant_id')->after('director_id')->constrained('employees', 'id');
            $table->text('address')->after('chief_accountant_id');
            $table->text('description')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('INN');
            $table->dropColumn('director_id');
            $table->dropColumn('chief_accountant_id');
            $table->dropColumn('address');
            $table->dropColumn('description');
        });
    }
};
