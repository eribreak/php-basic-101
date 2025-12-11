@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => ''])

<div class="mb-5">
    <label for="{{ $name }}" class="block mb-2 font-medium text-gray-strong">
        {{ $label }}
        @if($required)
            <span class="text-red">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        placeholder="{{ $placeholder }}"
        class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error($name) border-red @enderror"
    >
    @error($name)
        <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
