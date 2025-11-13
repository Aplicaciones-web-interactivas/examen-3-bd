{{--

    Archivos relacionados:                                                       
    - tailwind.config.js  → Define las variables CSS y colores customizados    
                                                                               
    VARIABLES CSS DISPONIBLES:                                                  
                                                                     
    1. --color-brand (Azul principal) = #4472CA                                 
       Uso: Para elementos principales, headers, acciones principales           
       Clases: .bg-brand, .text-brand, .border-brand                            
                                                                                  
    2. --color-accent (Rojo/Rosa destacado) = #FF6B6B                           
       Uso: Para llamadas a acción, alerts, elementos importantes              
       Clases: .bg-accent, .text-accent, .border-accent                         
                                                                                  
    3. --color-accent-light (Amarillo) = #FFE66D                                
       Uso: Botones secundarios, elementos de atención menos urgente            
      Clases: .bg-accent-light, .text-accent-light, .border-accent-light      
                                                                                  
    4. --color-text (Texto principal) = Color de texto base                     
       Uso: Textos, párrafos, contenido legible                                 
       Clases: .text-text                                                        
                                                                                  
    5. --color-surface (Fondo de componentes) = Color de fondo/card            
       Uso: Tarjetas, paneles, áreas contenidas                                 
       Clases: .bg-surface                                                       
                                                                                  

--}}

<section class="p-6 space-y-6 font-sans">
    {{-- SECCIÓN 1: TÍTULOS Y TEXTOS --}}
    <header class="space-y-2">
        {{-- 
          TÍTULO PRINCIPAL
          Clase: .text-4xl (tamaño grande)
          Clase: .text-brand (usa la variable --color-brand = #4472CA)
          Uso: Para títulos principales de página
        --}}
        <h1 class="text-4xl font-sans text-brand">Título de ejemplo — Paleta del proyecto</h1>
        
        {{-- 
          SUBTÍTULO / TEXTO SECUNDARIO
          Clase: .text-text (usa la variable --color-text)
          Uso: Para descripciones, subtítulos, texto de apoyo
          💡 Siempre usa .text-text para texto legible, NUNCA hardcodees colores
        --}}
        <p class="text-text">Subtítulo usando la variable <code>--color-text</code> (clase <code>text-text</code>).</p>
    </header>

    {{-- SECCIÓN 2: BOTONES Y LLAMADAS A ACCIÓN --}}
    <div class="flex items-center gap-4">
        {{-- 
          BOTÓN PRINCIPAL
          Clase: .bg-accent (fondo rojo #FF6B6B)
          Clase: .text-white (texto blanco para contraste)
          Clase: .hover:opacity-95 (efecto hover suave)
          Uso: Para acciones primarias (guardar, enviar, confirmar)
        --}}
        <button class="bg-accent text-white px-4 py-2 rounded shadow hover:opacity-95">Botón principal</button>
        
        {{-- 
          BOTÓN SECUNDARIO
          Clase: .bg-accent-light (fondo amarillo #FFE66D)
          Clase: .text-text (texto con color principal)
          Uso: Para acciones secundarias (cancelar, volver, opciones)
        --}}
        <button class="bg-accent-light text-text px-4 py-2 rounded shadow">Botón secundario</button>
        
        {{-- 
          BOTÓN CON BRAND
          Clase: .bg-brand (fondo azul #4472CA)
          Clase: .text-white (texto blanco para contraste)
          Uso: Para acciones con énfasis en marca (destacar, promover)
        --}}
        <button class="bg-brand text-white px-4 py-2 rounded shadow">Acción (brand)</button>
    </div>

    {{-- SECCIÓN 3: TARJETA / SURFACE --}}
    <div class="bg-surface text-text p-6 rounded shadow">
        {{-- 
          TARJETA / PANEL
          Clase: .bg-surface (usa variable --color-surface para fondo)
          Clase: .text-text (texto legible)
          Uso: Para contenedores, cards, paneles, modales
          💡 Siempre que necesites un "área contenida", usa .bg-surface
        --}}
        <h2 class="text-xl font-semibold mb-2">Card de ejemplo (surface)</h2>
        <p>Este panel usa <code>bg-surface</code> y texto <code>text-text</code>. Sirve como referencia para tarjetas y fondos.</p>
    </div>

    {{-- SECCIÓN 4: PALETA DE COLORES (SWATCHES VISUALES) --}}
    <div class="flex gap-4 items-center">
        {{-- 
          SWATCH 1: COLOR ACCENT
          Variable CSS: --color-accent
          Valor: #FF6B6B (Rojo/Rosa)
          Clases disponibles: .bg-accent, .text-accent, .border-accent
          Uso: Acciones principales, alerts, elementos importantes
        --}}
        <div class="space-y-2">
            <div class="w-32 h-12 rounded shadow" style="background:var(--color-accent)"></div>
            <div class="text-sm">
              --color-accent
              <br>
              <small class="text-text">#FF6B6B</small>
            </div>
          </div>
          
        {{-- 
          SWATCH 2: COLOR ACCENT LIGHT
          Variable CSS: --color-accent-light
          Valor: #FFE66D (Amarillo)
          Clases disponibles: .bg-accent-light, .text-accent-light, .border-accent-light
          Uso: Botones secundarios, elementos menos urgentes
        --}}
        <div class="space-y-2">
            <div class="w-32 h-12 rounded shadow" style="background:var(--color-accent-light)"></div>
            <div class="text-sm">
              --color-accent-light
              <br>
              <small class="text-text">#FFE66D</small>
            </div>
        </div>
        
        {{-- 
          SWATCH 3: COLOR BRAND
          Variable CSS: --color-brand
          Valor: #4472CA (Azul)
          Clases disponibles: .bg-brand, .text-brand, .border-brand
          Uso: Elementos principales, headers, marca
        --}}
        <div class="space-y-2">
            <div class="w-32 h-12 rounded shadow" style="background:var(--color-brand)"></div>
            <div class="text-sm">
              --color-brand
              <br>
              <small class="text-text">#4472CA</small>
            </div>
        </div>
      </div>

      {{-- FILA: COLORES SEMÁNTICOS ADICIONALES --}}
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
        <div class="space-y-2">
          <div class="w-32 h-12 rounded shadow" style="background:var(--color-background)"></div>
          <div class="text-sm">--color-background<br><small class="text-text">var(--color-zinc-50)</small></div>
        </div>
      </div>

      {{-- FILA: NEUTRALES (zinc) --}}
      <div class="flex gap-3 items-center mt-4">
        @foreach ([50,100,200,300,400,500,600,700,800,900] as $n)
          <div class="space-y-1 text-center">
            <div class="w-16 h-10 rounded shadow" style="background:var(--color-zinc-{$n})"></div>
            <div class="text-xs text-text">zinc-{{$n}}</div>
          </div>
        @endforeach
      </div>

          {{-- SECCIÓN 5: TIPOGRAFÍA --}}

    <div class="mt-8 border-t border-zinc-200 pt-6 space-y-4">
        <h2 class="text-xl font-semibold text-brand">Guía rápida de tipografía</h2>
        <p class="text-sm text-text">
            Usa siempre <code>font-sans</code> (mapea a <code>--font-sans</code>) y estos tamaños/pesos
            para mantener consistencia en todas las pantallas.
        </p>

        <div class="grid gap-4 md:grid-cols-2">
            {{-- TITULARES --}}
            <div class="space-y-3">
                {{-- Display / Hero --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">Display / Hero</p>
                    <p class="font-sans text-4xl md:text-5xl font-semibold text-brand">
                        Título grande para landing / hero
                    </p>
                </div>

                {{-- H1 --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">H1 — Título de página</p>
                    <p class="font-sans text-3xl font-semibold text-text">
                        Título principal de página
                    </p>
                </div>

                {{-- H2 --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">H2 — Subtítulo / sección</p>
                    <p class="font-sans text-2xl font-semibold text-text">
                        Título de sección
                    </p>
                </div>

                {{-- H3 --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">H3 — Bloques / cards</p>
                    <p class="font-sans text-xl font-medium text-text">
                        Título de card o bloque
                    </p>
                </div>
            </div>

            {{-- CUERPO DE TEXTO --}}
            <div class="space-y-3">
                {{-- Body / párrafo --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">Body — Texto principal</p>
                    <p class="font-sans text-base text-text">
                        Este es el tamaño base para párrafos. Úsalo para la mayoría de textos largos y
                        descripciones. Evita cambiar el tamaño de fuente manualmente salvo para títulos.
                    </p>
                </div>

                {{-- Secundario / ayuda --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">Body Sm — Texto secundario</p>
                    <p class="font-sans text-sm text-text">
                        Texto secundario, mensajes de ayuda, descripciones pequeñas debajo de labels, etc.
                    </p>
                </div>

                {{-- Caption / meta --}}
                <div>
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">Caption — Etiquetas / meta</p>
                    <p class="font-sans text-xs text-text uppercase tracking-wide">
                        Pequeñas etiquetas, estados, metadata, timestamp
                    </p>
                </div>

                {{-- Estados / énfasis --}}
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-wide text-text opacity-70">Pesos recomendados</p>
                    <p class="font-sans text-sm text-text">
                        <span class="font-normal">Regular (400)</span> — texto normal<br>
                        <span class="font-medium">Medium (500)</span> — resaltar sin gritar<br>
                        <span class="font-semibold">Semibold (600)</span> — títulos y botones
                    </p>
                </div>
            </div>
        </div>
    </div>


</section>
