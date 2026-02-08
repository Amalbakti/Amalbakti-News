<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class NormalizeCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultColor = '#6366f1';

        // Fill missing fields
        Category::get()->each(function (Category $category) use ($defaultColor) {
            $updated = false;

            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
                $updated = true;
            }

            if (empty($category->description)) {
                $category->description = 'No description available.';
                $updated = true;
            }

            if (empty($category->color)) {
                $category->color = $defaultColor;
                $updated = true;
            }

            if ($updated) {
                $category->save();
            }
        });

        // Ensure unique slugs by appending numeric suffixes if needed
        $seen = [];
        Category::orderBy('id')->get()->each(function (Category $category) use (&$seen) {
            $slug = $category->slug;
            if (!isset($seen[$slug])) {
                $seen[$slug] = 1;
                return;
            }

            $i = ++$seen[$slug];
            $newSlug = $slug . '-' . $i;
            while (Category::where('slug', $newSlug)->exists()) {
                $i++;
                $newSlug = $slug . '-' . $i;
            }

            $category->slug = $newSlug;
            $category->save();
        });

        $this->command->info('Categories normalized.');
    }
}
