<?php

namespace Database\Factories;
use App\Models\Imagen;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imagen>
 */
class ImagenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // URLs reales de imágenes (puedes agregar más)
        $urls = [
            "https://shop.dupapier.com.mx/cdn/shop/files/ESCOBA-DE-PLASTICO-PERICO-TIPO-CEPILLO_E001-003_1.jpg?v=1761241439",
            "https://m.media-amazon.com/images/I/61b+-sLTZuL._AC_UF894,1000_QL80_.jpg",
            "https://www.taurus.com.mx/cdn/shop/files/17b792a3-3837-48bd-bc36-bbc42cdef878.jpg?v=1738161401&width=1946",
            "https://macstoreonline.com.mx/img/sku/accugr020_FZ.jpg",
            "https://cdn-dynmedia-1.microsoft.com/is/image/microsoftcorp/Surface-Laptop-Go-3_OG_Twitter-image?scl=1",
            "https://mx-media.hptiendaenlinea.com/catalog/product/cache/b3b166914d87ce343d4dc5ec5117b502/7/F/7F211LT-1_T1704928096.png",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSR_PjR7mAKttaP94i5D9DHM5ONOYCONy_WLA&s",
            "https://eurobakery.com.mx/wp-content/uploads/2023/05/ITALIANA-2.jpg",
            "https://media.capsamex.com.mx/products/MO6849-05.jpg",
        ];

        return [
            'nombre' => $this->faker->sentence(3),
            'imagen_url' => $this->faker->randomElement($urls),
        ];
    }
}
