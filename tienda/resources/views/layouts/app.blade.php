@php
    $pageTitle = trim($__env->yieldContent('title', $title ?? ''));
@endphp

<x-layouts.app :title="$pageTitle !== '' ? $pageTitle : null">
    @yield('content')
</x-layouts.app>
