<div>
    <!-- Blog Article -->
    <div class="max-w-3xl px-4 pt-6 pb-12 mx-auto lg:pt-10 sm:px-6 lg:px-8">
        <div class="max-w-2xl">

            <!-- Content -->
            <div class="space-y-5 md:space-y-8">
                <div class="space-y-1">
                    <h2 class="text-2xl font-bold md:text-3xl dark:text-white">{{ $page->name }}
                    </h2>
                    <span class="text-sm text-gray-400">Published At {{ $page->created_at }}</span>
                </div>

                <div class="space-y-3 text-lg prose text-gray-800 dark:text-neutral-200">
                    {!! Str::markdown($page->content ?? '') !!}
                </div>

            </div>
            <!-- End Content -->
        </div>
    </div>
    <!-- End Blog Article -->
</div>
