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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'resolved', 'canceled'])->default('open');

            // requester
            $table->foreignId('requested_by')->constrained('users')->nullOnDelete();

            // assignee
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // service
            $table->foreignId('service_id')->constrained()->nullOnDelete();

            // support group
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
