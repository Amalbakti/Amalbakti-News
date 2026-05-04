@props([
    'on'
])

<div
    x-data="{ showToast: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => {
        showToast = true;
        timeout = setTimeout(() => {
            showToast = false;
        }, 3000);
    })"
    x-show.transition.out.opacity.duration-1500ms="showToast"
    x-transition:leave.opacity.duration-1500ms
    style="display: none"
    {{ $attributes->merge(['class' => 'fixed bottom-5 right-5 z-50 bg-indigo-600 text-sm p-4 rounded-lg shadow-lg text-white font-semibold w-96'
    ]) }}>
    <p>{{ $slot }}</p>
</div>