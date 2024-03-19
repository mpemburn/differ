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
        Schema::create('comparison_results', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->integer('test_number')->default(1);
            $table->dateTime('before_date');
            $table->dateTime('after_date');
            $table->string('filename');
            $table->string('diff_percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_results');
    }
};
