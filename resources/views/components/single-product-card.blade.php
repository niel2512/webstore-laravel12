<a class="flex flex-col bg-white group rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70"
    href="{{ route('product') }}">
    {{-- {{ dd($product) }} --}}
    <img class="object-cover rounded-md aspect-square" src="{{ $product->cover_url }}" alt="{{ $product->name }}">
    <div class="py-5">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white">
            {{ $product->name }}
        </h3>
        <span class="text-sm text-gray-500">
            {{ $product->category_tags }}
        </span>
        <p class="mt-1 font-semibold text-black dark:text-blue-500">
            {{ $product->price_formatted }}
        </p>
    </div>
</a>
