<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questoes', function (Blueprint $table) {
            $table->unsignedBigInteger('edital_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert is risky if there are nulls, but for strict revert:
        // $table->unsignedBigInteger('edital_id')->nullable(false)->change();
    }
};
