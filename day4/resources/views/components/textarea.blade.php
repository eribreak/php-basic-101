@props(['name', 'label', 'value' => '', 'required' => false, 'placeholder' => '', 'rows' => 4])

<div class="mb-5">
    <label for="{{ $name }}" class="block mb-2 font-medium text-gray-strong">
        {{ $label }}
        @if($required)
            <span class="text-red">*</span>
        @endif
    </label>
    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        @if($required) required @endif
        placeholder="{{ $placeholder }}"
        class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent resize-y min-h-[100px] @error($name) border-red @enderror"
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
