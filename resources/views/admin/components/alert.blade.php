<div class="mb-4 p-4 rounded-md {{
    $type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
    $type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
    $type === 'warning' ? 'bg-yellow-100 border border-yellow-400 text-yellow-700' :
    'bg-red-100 border border-red-400 text-red-700'
}}" role="alert">
    @if($dismissible)
        <div class="flex justify-between items-start">
            <div class="flex-grow">
                @if($title)
                    <h3 class="font-medium">{{ $title }}</h3>
                @endif
                <div>{{ $slot }}</div>
            </div>
            <button type="button" class="ml-4" onclick="this.parentElement.parentElement.remove()">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @else
        @if($title)
            <h3 class="font-medium">{{ $title }}</h3>
        @endif
        <div>{{ $slot }}</div>
    @endif
</div>

