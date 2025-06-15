<div>
    <button wire:click="remove()" class="text-red-500 flex items-center cursor-pointer">
        Delete
        <div wire:loading wire:loading.attr="disabled"
            class="animate-spin inline-block size-4 border-3 border-current border-t-transparent text-blue-600 rounded-full dark:text-white"
            role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
    </button>
</div>
