<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->

        {{-- herro --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-900 mb-12 h-[400px]">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-75">
        </div>
        <div class="relative bg-gray-50 rounded-lg overflow-hidden mb-12">
            @if($posts->isNotEmpty() && $posts->first()->featured_image)
                <img src="{{ Storage::url($posts->first()->featured_image) }}" alt="{{ $posts->first()->title }}" class="w-full h-96 object-cover rounded-lg mb-8">
            @endif          
        </div>
        <div class="absolute inset-0 flex flex-col items-start justify-center px-6 sm:px-12 lg:px-16">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $posts->isNotEmpty() ? $posts->first()->title : 'Selamat Datang di Berita Terkini!' }}
            </h1>
            <p class="text-lg text-gray-200 mb-6">
                {{ $posts->isNotEmpty() ? Str::limit($posts->first()->excerpt, 150) : 'Dapatkan informasi terbaru seputar kegiatan, acara, dan berita menarik lainnya dari Paguyuban.' }}
            </p>
            @if($posts->isNotEmpty())
                <a href="{{ route('blog.show', $posts->first()->slug) }}" wire:navigate
                    class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    Baca selengkapnya...
                </a>
            @endif
        </div>
        </div>   
        {{-- end herro --}}

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Search -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Categories</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('selectedCategory', '')"
                            class="w-full text-left px-3 py-2 rounded-md text-sm {{ $selectedCategory === '' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            All Categories
                        </button>
                        @foreach($categories as $category)
                            <button wire:click="$set('selectedCategory', '{{ $category->slug }}')"
                                class="w-full text-left px-3 py-2 rounded-md text-sm flex items-center justify-between {{ $selectedCategory === $category->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full mr-2"
                                        style="background-color: {{ $category->color }}"></span>
                                    {{ $category->name }}
                                </span>
                                <span class="text-xs text-gray-500">({{ $category->posts_count }})</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            @if($tag->posts_count > 0)
                                <button wire:click="$set('selectedTag', '{{ $tag->slug }}')"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $selectedTag === $tag->slug ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    {{ $tag->name }} ({{ $tag->posts_count }})
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Clear Filters -->
                @if($search || $selectedCategory || $selectedTag)
                    <button wire:click="clearFilters"
                        class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                        Clear Filters
                    </button>
                @endif
            </aside>

            <div class="lg:col-span-3">
                <!-- Posts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($posts as $post)
                        <article wire:key="post-{{ $post->id }}"
                            class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}" wire:navigate>
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover">
                                </a>
                            @else
                                <a href="{{ route('blog.show', $post->slug) }}" wire:navigate>
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-4xl text-white font-bold">{{ substr($post->title, 0, 1) }}</span>
                                    </div>
                                </a>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->user->name }}</span>
                                    @if ($post->views_count > 0)
                                        <span class="mx-2">•</span>
                                        <span>{{ number_format($post->views_count) }} {{ Str::plural('view', $post->views_count) }}</span>                                    
                                    @endif
                                </div>

                                <h2 class="text-xl font-bold text-gray-900 mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" wire:navigate class="hover:text-indigo-600">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($post->excerpt, 120) }}
                                    </p>
                                @endif

                                <a href="{{ route('blog.show', $post->slug) }}" wire:navigate
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Read more →
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">No posts found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>

        {{-- subscribe section --}}
        <div class="mt-12">
            <livewire:blog.subscribe/>
        </div>
    </div>
</div>