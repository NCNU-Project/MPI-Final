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
        # add file status
        Schema::create('file_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->string('filename');
            $table->dropColumn('status');
            $table->foreignId('file_status_id')->nullable();

            // $table->foreignId('file_status_id')->constrainted();
            # relations to status table
            $table->foreign('file_status_id')->references('id')->on('file_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_statuses');
    }
};
