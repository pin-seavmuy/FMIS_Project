@props(['placeholder' => 'Search...'])

<div class="relative w-full max-w-xs">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <span class="icon-[tabler--search] text-gray-400 w-5 h-5"></span>
    </div>
    <input 
        {{ $attributes->merge(['class' => 'input input-bordered w-full pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all']) }}
        type="text" 
        placeholder="{{ $placeholder }}"
    />
</div>
