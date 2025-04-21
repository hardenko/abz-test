<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    public function makeCall(string $uri, array $parameters = [], array $headers = []): TestResponse
    {
        $headers = array_merge(['Accept' => 'application/json'], $headers);

        return $this->json($this->getHttpMethod(), $uri, $parameters, $headers);
    }
}
