<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-3 py-2 border ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300') . ' rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500']) }}
        >{{ $value }}</textarea>
    @elseif($type === 'select')
        <select
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-3 py-2 border ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300') . ' rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500']) }}
        >
            {{ $slot }}
        </select>
    @elseif($type === 'checkbox')
        <div class="flex items-center">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ $value }}"
                {{ $checked ? 'checked' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => 'rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50']) }}
            >
            <span class="ml-2 text-sm text-gray-700">{{ $checkboxLabel }}</span>
        </div>
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-3 py-2 border ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300') . ' rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500']) }}
        >
    @endif

    @if($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

