@props([
    'id' => uniqid(),
])

<div class="p-2">
    {{ $slot }}
</div>
