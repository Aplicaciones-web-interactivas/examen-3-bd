@if(session('success'))
    <div class="rounded-md border-l-4 border-green-500 bg-green-50 px-3 py-2 text-sm text-green-700 dark:border-green-400 dark:bg-green-900/30 dark:text-green-300">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="rounded-md border-l-4 border-red-500 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-400 dark:bg-red-900/30 dark:text-red-300">
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div class="rounded-md border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-500 dark:bg-red-900/30 dark:text-red-300">
        <ul class="list-disc pl-4 space-y-0.5">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif