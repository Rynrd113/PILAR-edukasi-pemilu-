<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 ' .
        ($variant === 'primary' ? 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500' :
         $variant === 'secondary' ? 'bg-gray-500 hover:bg-gray-600 text-white focus:ring-gray-500' :
         $variant === 'danger' ? 'bg-red-800 hover:bg-red-900 text-white focus:ring-red-500' :
         $variant === 'success' ? 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500' :
         'bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 focus:ring-red-500')
    ]) }}
>
    @if($icon)
        <span class="mr-1">{!! $icon !!}</span>
    @endif
    {{ $slot }}
</button>

