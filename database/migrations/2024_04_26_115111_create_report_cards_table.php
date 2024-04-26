<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->date('date');
            $table->unsignedBigInteger('organization_id');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('author_id');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
