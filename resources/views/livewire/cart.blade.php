<div>
    <div class="container mx-auto max-w-[85rem] w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-10 md:grid-cols-10">
            <div class="md:col-span-7">
                <h1 class="mb-5 text-2xl font-extralight">Shopping Bag</h1>
                <div class="grid gap-5">
                    @forelse ($items as $item)
                        {{-- <x-single-product-cart /> --}}
                        <div class="flex items-center gap-5 pb-5 border-b border-gray-200">
                            <div class="relative w-40 h-40 overflow-hidden rounded-xl">
                                <img class="object-coversize-full" src="{{ $item->product()->cover_url }}"
                                    alt="{{ $item->product()->name }}">
                            </div>
                            <div class="flex items-center">
                                <div class="py-5">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                        {{ $item->product()->name }}
                                    </h3>
                                    <h2 class="text-sm text-gray-800 dark:text-blue-500">
                                        {{ $item->product()->category_tags }}</h2>
                                    <div class="flex items-center gap-2 my-5">

                                        <livewire:add-to-cart wire:key="add-to-cart-{{ $item->sku }}"
                                            :product="$item->product()" />

                                        <p class="px-3 py-2 mt-1 text-xl font-semibold text-black dark:text-white">
                                            {{ $item->product()->price_formatted }}
                                        </p>
                                        {{-- Tombol Hapus Data CheckOut --}}
                                        <livewire:cart-item-remove :product="$item->product()" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>No Product</div>
                    @endforelse
                </div>
            </div>
            <div class="md:col-span-3">
                <h1 class="mb-5 text-2xl font-extralight">Order Summary</h1>
                <div class="grid gap-5">
                    <!-- List Group -->
                    <ul class="flex flex-col mt-3">
                        <li
                            class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Sub Total</span>
                                <span>{{ $sub_total }}</span>
                            </div>
                        </li>
                        <li
                            class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Shipping</span>
                                <span>—</span>
                            </div>
                        </li>
                        <li
                            class="inline-flex items-center px-4 py-3 -mt-px text-sm font-semibold text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Total</span>
                                <span>{{ $total }}</span>
                            </div>
                        </li>
                    </ul>
                    <!-- End List Group -->
                    <button type="button" wire:click="checkout" wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg cursor-pointer gap-x-2 hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Checkout Now
                        <div wire:loading wire:loading.attr="disabled"
                            class="animate-spin inline-block size-4 border-3 border-current border-t-transparent text-blue-600 rounded-full dark:text-white"
                            role="status" aria-label="loading">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
