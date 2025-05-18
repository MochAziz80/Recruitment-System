<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->json('requirements')->nullable(); // JSON field
            $table->boolean('is_active')->default(true); // boolean
            $table->dateTime('posted_at')->nullable(); // datetime
            $table->softDeletes(); // for soft-delete
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
