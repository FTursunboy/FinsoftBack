<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->renameColumn('schedule', 'schedule_id');
        });
    }

    public function down(): void
    {

    }
};
