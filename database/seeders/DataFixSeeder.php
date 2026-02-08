<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class DataFixSeeder extends Seeder
{
    public function run(): void
    {
        // Fix misspelled status values
        DB::table('posts')->where('status', 'publised')->update(['status' => 'published']);

        // Fix missing slugs
        Post::whereNull('slug')->orWhere('slug', '')->get()->each(function ($p) {
            $p->slug = Str::slug($p->title ?: 'post-'.$p->id);
            $p->save();
        });

        // Create some example categories if none exist
        $names = ['News', 'Announcement', 'Tech'];
        foreach ($names as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'color' => null]
            );
        }

        // Attach a category to posts that have none
        $catIds = Category::pluck('id')->toArray();
        if (!empty($catIds)) {
            Post::doesntHave('categories')->get()->each(function ($p) use ($catIds) {
                $p->categories()->attach([$catIds[array_rand($catIds)]]);
            });
        }

        // Create some example tags and attach to posts
        $tagNames = ['Updates', 'Laravel', 'Tips'];
        foreach ($tagNames as $t) {
            Tag::firstOrCreate(['slug' => Str::slug($t)], ['name' => $t]);
        }

        $tagIds = Tag::pluck('id')->toArray();
        if (!empty($tagIds)) {
            Post::doesntHave('tags')->get()->each(function ($p) use ($tagIds) {
                $p->tags()->attach([$tagIds[array_rand($tagIds)]]);
            });
        }
    }
}
