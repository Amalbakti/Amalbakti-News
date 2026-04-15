<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ceate categories
        $categories = [
            [
                'name' => 'Technology', 'description' => 'Tech news and tutorials',
                'color' => '#3b82f6'],
            [
                'name' => 'Business', 'description' => 'Business insights and strategies',
                'color' => '#10b981'],
            [
                'name' => 'Lifestyle', 'description' => 'Lifestyle tips and stories',
                'color' => '#f59e0b'],
            [
                'name' => 'Travel', 'description' => 'Travel guides and experiences',
                'color' => '#8b5cf6'],
            [
                'name' => 'Religion', 'description' => 'Religious insights and stories',
                'color' => '#ec4899'],
            [
                'name' => 'Pariwisata', 'description' => 'Travel guides and experiences',
                'color' => '#8b5cf6'],
            [
                'name' => 'Food', 'description' => 'Recipes and food reviews',
                'color' => '#ef4444'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

        // Crete tags
        $tags = [
            'Laravel',
            'PHP',
            'JavaScript',
            'Vue.JS',
            'React',
            'livewire',
            'Tailwind CSS',
            'Mobile App',
            'HTML',
            'Web Development',
            'Design',
            'Tutorials',
        ];
        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag]);
        }
    }
}
