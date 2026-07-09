<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_public_visitor_registration_route_is_registered(): void
    {
        $this->assertTrue(Route::has('visitor-registration.create'));
        $this->assertTrue(Route::has('visitor-registration.store'));
    }
}
