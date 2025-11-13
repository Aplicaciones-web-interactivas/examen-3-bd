<!doctype html>
<html lang="es">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-background text-text">

        <main class="p-6 space-y-6 font-sans">

            {{-- SECCIÓN: Guía visual de colores y tipografía --}}

            <section>
                <header class="space-y-2">
                    <h1 class="text-4xl font-sans text-brand">Título de ejemplo — Paleta del proyecto</h1>
                    <p class="text-text">Subtítulo usando la variable <code>--color-text</code> (clase <code>text-text</code>).</p>
                </header>

                <div class="flex items-center gap-4 mt-4">
                    <button class="bg-accent text-white px-4 py-2 rounded shadow hover:opacity-95">Botón principal</button>
                    <button class="bg-accent-light text-text px-4 py-2 rounded shadow">Botón secundario</button>
                    <button class="bg-brand text-white px-4 py-2 rounded shadow">Acción (brand)</button>
                </div>

                <div class="bg-surface text-text p-6 rounded shadow mt-6">
                    <h2 class="text-xl font-semibold mb-2">Card de ejemplo (surface)</h2>
                    <p>Este panel usa <code>bg-surface</code> y texto <code>text-text</code>. Sirve como referencia para tarjetas y fondos.</p>
                </div>

                <div class="flex gap-4 items-center mt-6">
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-accent)"></div>
                        <div class="text-sm">--color-accent<br><small class="text-text">#FF6B6B</small></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-accent-light)"></div>
                        <div class="text-sm">--color-accent-light<br><small class="text-text">#FFE66D</small></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-brand)"></div>
                        <div class="text-sm">--color-brand<br><small class="text-text">#4472CA</small></div>
                    </div>
                </div>

                <div class="flex gap-4 items-center mt-4">
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-success)"></div>
                        <div class="text-sm">--color-success<br><small class="text-text">#16A34A</small></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-danger)"></div>
                        <div class="text-sm">--color-danger<br><small class="text-text">#EF4444</small></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-warning)"></div>
                        <div class="text-sm">--color-warning<br><small class="text-text">#F59E0B</small></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-12 rounded shadow" style="background:var(--color-info)"></div>
                        <div class="text-sm">--color-info<br><small class="text-text">#0EA5E9</small></div>
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-200 pt-6 space-y-4">
                    <h2 class="text-xl font-semibold text-brand">Guía rápida de tipografía</h2>
                    <p class="text-sm text-text">Usa siempre <code>font-sans</code> (mapea a <code>--font-sans</code>) y los estilos recomendados para mantener consistencia.</p>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div class="space-y-3">
                            <p class="text-xs uppercase tracking-wide text-text opacity-70">Display / Hero</p>
                            <p class="font-sans text-4xl md:text-5xl font-semibold text-brand">Título grande para landing / hero</p>
                        </div>

                        <div class="space-y-3">
                            <p class="text-xs uppercase tracking-wide text-text opacity-70">Body — Texto principal</p>
                            <p class="font-sans text-base text-text">Este es el tamaño base para párrafos. Úsalo para la mayoría de textos largos y descripciones.</p>
                        </div>
                    </div>
                </div>

            </section>

        </main>

    </body>
</html>
