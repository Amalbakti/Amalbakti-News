<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure these categories are removed if they exist
        DB::table('categories')->whereIn('slug', ['tech', 'health'])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $now = Carbon::now();
        DB::table('categories')->insert([
            ['name' => 'Tech', 'slug' => 'tech', 'description' => null, 'color' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Health', 'slug' => 'health', 'description' => null, 'color' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
};
