@props([
    'type' => session()->has('success') ? 'success' : (session()->has('error') ? 'error' : 'info'),
    'message' => session('success') ?? (session('error') ?? ''),
])

@if ($message || $errors->any())
    <div
        class="p-4 mb-4 rounded-md text-sm @if ($type === 'success') bg-green-100 text-green-800 @elseif($type === 'error') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
        @if ($message)
            {{ $message }}
        @endif

        @if ($errors->any())
            <ul class="mt-1 list-dist list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
