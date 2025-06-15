<div class="gap-2 my-5">
    <div class="flex items-center">
        <div x-data="{ quantity: @entangle('quantity') }" class="flex gap-2 items-centerm y-5">
            <div
                class="inline-block px-3 py-2 bg-white border border-gray-200 rounded-lg dark:bg-neutral-900 dark:border-neutral-700">
                <div class="flex items-center gap-x-1.5">
                    <!-- Tombol tkurang -->
                    <button
                        class="inline-flex items-center justify-center text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md cursor-pointer size-6 gap-x-2 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        @click="if(quantity > 0) quantity--">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <button type="button" wire:click="addToCart"
                class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg cursor-pointer gap-x-2 hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Add To Cart
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m5 11 4-7"></path>
                    <path d="m19 11-4-7"></path>
                    <path d="M2 11h20"></path>
                    <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8c.9 0 1.8-.7 2-1.6l1.7-7.4"></path>
                    <path d="m9 11 1 9"></path>
                    <path d="M4.5 15.5h15"></path>
                    <path d="m15 11-1 9"></path>
                </svg>
                <div wire:loading wire:loading.attr="disabled"
                    class="animate-spin inline-block size-4 border-3 border-current border-t-transparent text-blue-600 rounded-full dark:text-white"
                    role="status" aria-label="loading">
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </div>
    </div>
    <div class="text-sm my-2 dark:text-blue-500">Stock: {{ $stock }}</div>
    @error('quantity')
        <div class="text-red-500 text-xs dark:text-blue-500">{{ $message }}</div>
    @enderror
</div>
