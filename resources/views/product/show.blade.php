<x-layouts.app>
    <div class="container mx-auto max-w-[85rem] w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 gap-10 my-5 md:grid-cols-10">
            <div class="grid grid-cols-1 gap-2 md:col-span-7">
                <div class="w-full">
                    <img src="{{ $product->cover_url }}" alt="{{ $product->name }} Cover"
                        class="object-cover w-full rounded-md aspect-3/2 md:col-span-3">
                    <div class="grid grid-cols-1 gap-2 my-2 md:grid-cols-3 md:col-span-7">
                        @foreach ($product->gallery as $key => $image)
                            <img src="{{ $image }}" alt="image-{{ $key }}"
                                class="object-cover rounded-md aspect-square" />
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="md:col-span-3">
                <div class="flex flex-col gap-2">
                    <div>
                        <h1 class="text-3xl font-semibold dark:text-white">{{ $product->name }}</h1>
                        <h2 class="text-sm text-gray-800 dark:text-blue-500">{{ $product->category_tags }}
                        </h2>
                        <h3 class="text-xs text-gray-500">sku: {{ $product->sku }}</h2>
                    </div>
                    <span class="mt-2 text-2xl font-bold dark:text-blue-500">{{ $product->price_formatted }}</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 my-5">
                        <div x-data="{ quantity: 1 }" class="flex gap-2 items-centerm y-5">
                            <div
                                class="inline-block px-3 py-2 bg-white border border-gray-200 rounded-lg dark:bg-neutral-900 dark:border-neutral-700">
                                <div class="flex items-center gap-x-1.5">
                                    <button
                                        class="inline-flex items-center justify-center text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md cursor-pointer size-6 gap-x-2 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        @click="if(quantity > 0) quantity--">
                                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14"></path>
                                        </svg>
                                    </button>
                                    <!-- Input jumlah -->
                                    <input
                                        class="p-0 w-6 bg-transparent border-0 text-gray-800 text-center focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none dark:text-white"
                                        style="-moz-appearance: textfield;" type="number" x-model.number="quantity"
                                        @input="if(quantity < 0) quantity = 0" min="0">


                                    <!-- Tombol tambah -->
                                    <button type="button"
                                        class="inline-flex items-center justify-center text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md cursor-pointer size-6 gap-x-2 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        @click="quantity++">
                                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14"></path>
                                            <path d="M12 5v14"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button"
                                class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg cursor-pointer gap-x-2 hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Add To Cart
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m5 11 4-7"></path>
                                    <path d="m19 11-4-7"></path>
                                    <path d="M2 11h20"></path>
                                    <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8c.9 0 1.8-.7 2-1.6l1.7-7.4"></path>
                                    <path d="m9 11 1 9"></path>
                                    <path d="M4.5 15.5h15"></path>
                                    <path d="m15 11-1 9"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold dark:text-white">Description</h3>
                    <div class="my-2 prose text-gray-800 dark:text-neutral-200">
                        {!! Str::markdown($product->description) !!}
                    </div>
                </div>
            </div>
            <div class="md:col-span-10">
                {{-- <x-product-sections title="You may also like" :url="route('product-catalog')" /> --}}
            </div>

        </div>
    </div>
</x-layouts.app>
