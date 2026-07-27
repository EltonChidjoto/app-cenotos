<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installed_modules', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('version', 30);
            $table->string('status', 30)->default('active');
            $table->timestamp('installed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installed_modules');
    }
};
