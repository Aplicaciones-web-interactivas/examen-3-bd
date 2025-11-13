<h1>Lista de Imágenes</h1>

@foreach($imagenes as $img)
    <p>
        <strong>{{ $img->nombre }}</strong><br>
        <img src="{{ $img->imagen_url }}" width="150"><br>
    </p>
@endforeach
<a href="/testing">Volver a Testing</a>
