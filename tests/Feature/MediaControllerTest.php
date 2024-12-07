<?php

namespace Tests\Feature;

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
            'title' => '', // Invalid title
            'genre' => 'Sci-Fi',
            'availability' => 'invalid_status', // Invalid availability
            'rental_price' => -10, // Invalid rental price
            'media_type' => '',
        ];

        // Perform the POST request
        $response = $this->postJson('/api/medias', $data);

        // Assert the response
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'availability', 'rental_price', 'media_type']);
    }    
}
