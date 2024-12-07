<?php

namespace Tests\Feature;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    public function test_store_creates_a_new_media_record(): void
    {
        $titles = ['Interestellar', 'Jurassic Park', 'The Ring', 'Ju-on', 'Invocação do Mal 1', 'The Goonies', 'Spider-man', 'Spider-man 2', 'Back to the Future', 'O Silêncio dos Inocentes', 'Psicose', 'O Iluminado', 'Ghost'];
        $genres = ['Sci-fi', 'Aventura', 'Terror', 'Suspense', 'Romance', 'Comédia'];
        $availability = ['available', 'rented'];
        $rental_price = 4.99;
        $media_types = ['VHS', 'DVD', 'Super Nintendo'];

        $data = [
            'title' => $titles[array_rand($titles)],
            'genre' => $genres[array_rand($genres)],
            'availability' => $availability[array_rand($availability)],
            'rental_price' => $rental_price,
            'media_type' => $media_types[array_rand($media_types)],
        ];

        $response = $this->postJson('/api/medias', $data);
        $response->assertStatus(201)->assertJson($data);
        $this->assertDatabaseHas('medias', $data);
    }

    public function test_store_fails_with_invalid_data()
    {
        $data = [
            'title' => '',
            'genre' => 'Sci-Fi',
            'availability' => 'invalid_status',
            'rental_price' => -10,
            'media_type' => '',
        ];

        $response = $this->postJson('/api/medias', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'title', 
                    'availability', 
                    'rental_price', 
                    'media_type',
                ]);
    }

    public function test_store_update_media_successfully() 
    {
        $media = Media::factory()->create();

        $data = [
            'title' => 'Click',
            'genre' => 'Drama, Comédia',
            'availability' => 'available',
            'rental_price' => 4.99,
            'media_type' => 'DVD'
        ];

        $response = $this->putJson("/api/medias/{$media->id}", $data);
        $response->assertStatus(200)->assertJsonFragment([
            'title' => 'Click',
            'genre' => 'Drama, Comédia',
            'availability' => 'available',
            'rental_price' => 4.99,
            'media_type' => 'DVD'
        ]);

        $this->assertDatabaseHas('medias', [
            'id' => $media->id,
            'title' => 'Click'
        ]);

    }
}
