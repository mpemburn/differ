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
        Schema::create('page_links', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('image');
            $table->string('when');
            $table->string('source_file');
            $table->string('test_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_links');
    }
};
