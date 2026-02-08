<x-layouts::app :title="__('Dashboard')">
    @php
        $user = auth()->user();
        $isAdmin = $user && ($user->hasRole('admin') || $user->hasRole('editor'));

        $postsQuery = $isAdmin ? \App\Models\Post::query() : \App\Models\Post::where('user_id', $user->id);

        $stats = [
            'total_posts' => (clone $postsQuery)->count(),
            'published_posts' => (clone $postsQuery)->where('status', 'published')->count(),
            'draft_posts' => (clone $postsQuery)->where('status', 'draft')->count(),
            'total_views' => (clone $postsQuery)->sum('views_count'),
            'total_comments' => $isAdmin ? \App\Models\Comment::count() : \App\Models\Comment::whereHas('post', fn ($q) => $q->where('user_id', $user->id))->count(),
            'total_users' => $isAdmin ? \App\Models\User::count() : null,
        ];

        $mostViewedPosts = (clone $postsQuery)
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        $recentComments = \App\Models\Comment::with(['user', 'post'])
            ->when(!$isAdmin, fn ($q) => $q->whereHas('post', fn ($query) => $query->where('user_id', $user->id)))
            ->latest()
            ->take(5)
            ->get();

        // views per day for last 7 days
        $rawViews = \App\Models\PostView::select(
                \DB::raw("DATE(viewed_at) as date"),
                \DB::raw('COUNT(*) as count')
            )
            ->when(!$isAdmin, fn ($q) => $q->whereHas('post', fn ($query) => $query->where('user_id', $user->id)))
            ->where('viewed_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($row) => \Carbon\Carbon::parse($row->date)->format('d-m-Y'));

        $viewsData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('d-m-Y');
            $dateLabel = $date->format('d M');
            $viewsData->push([
                'date' => $dateLabel,
                'count' => isset($rawViews[$dateKey]) ? (int) $rawViews[$dateKey]->count : 0,
            ]);
        }
    @endphp


        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome back, {{ optional(auth()->user())->name }}!</p>
        <div class="mb-6 flex justify-end">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Visit Blog
        </a>
    </div>
    {{-- @island
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"></div>
    @endisland --}}

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Posts</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['total_posts'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium">{{ $stats['published_posts'] }} published</span>
                <span class=" mx-2 text-gray-500">|</span>
                <span class="text-yellow-600 font-medium">{{ $stats['draft_posts'] }} drafts</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-3xl font-bold text-purple-900 mt-2">{{ $stats['total_views'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Comments</p>
                    <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $stats['total_comments'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72A7.963 7.963 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if ($isAdmin)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-6a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500">Registered authors & readers</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900">Views Chart</h2>
            <div class="mt-4">
                <canvas id="viewsChart" width="400" height="180"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Most Viewed Posts</h2>
            <ul class="space-y-4">
                @forelse ($mostViewedPosts as $post)
                    <li class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 font-medium hover:underline">{{ $post->title }}</a>
                            <p class="text-xs text-gray-500 mt-1">{{ optional($post->published_at)->format('d M, Y') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-800">{{ number_format($post->views_count) }} views</span>
                        </div>
                    </li>
                @empty
                    <p class="text-sm text-gray-500">No published posts yet.</p>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Comments</h2>
            <div class="space-y-4">
                @forelse ($recentComments as $comment)
                    <div class="flex items-start space-x-3 pb-4 border-gray-200 last:border-0 last:pb-0">
                        @php 
                            $userName = optional($comment->user)->name ?? 'User';
                            $initials = strtoupper(substr($userName, 0, 1));
                        @endphp
                        <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center flex-shrink-0 text-xs font-semibold">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $userName }}
                                <span class="text-gray-500 font-normal">commented on</span>
                                <a href="{{ route('posts.edit', $comment->post) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">{{ \Illuminate\Support\Str::limit($comment->post->title, 30) }}</a>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($comment->content, 100) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent comments.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Views (last 7 days)</h2>
            <ul class="space-y-2">
                @foreach($viewsData as $row)
                    <li class="flex justify-between text-sm text-gray-700">
                        <span>{{ $row['date'] }}</span>
                        <span class="font-medium">{{ $row['count'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($viewsData->pluck('date'));
        const data = @json($viewsData->pluck('count'));

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('viewsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Views',
                        data: data,
                        borderColor: 'rgba(79,70,229,1)',
                        backgroundColor: 'rgba(79,70,229,0.1)',
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: { responsive: true }
            });
        });
    </script>

</x-layouts::app>
