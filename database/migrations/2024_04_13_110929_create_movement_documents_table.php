<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movement_documents', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('doc_number')->unique();
            $table->timestamp('date');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('sender_storage_id');
            $table->unsignedBigInteger('recipient_storage_id');
            $table->unsignedBigInteger('author_id');
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_documents');
    }
};
