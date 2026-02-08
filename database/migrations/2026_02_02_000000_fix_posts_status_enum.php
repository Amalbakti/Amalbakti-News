<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the correctly spelled 'published' value to the enum
        DB::statement("ALTER TABLE `posts` MODIFY `status` ENUM('draft','published','archived') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous misspelled enum value
        DB::statement("ALTER TABLE `posts` MODIFY `status` ENUM('draft','publised','archived') NOT NULL DEFAULT 'draft'");
    }
};
