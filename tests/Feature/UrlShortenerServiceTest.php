<?php

use App\Models\Links;
use App\Services\UrlShortenerService;

use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\{assertDatabaseCount};

it('will encode a URL into a short URL', function () {
    $service = new UrlShortenerService;
    $result = $service->encode('http://example.test');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toMatch('/[A-Za-z0-9]{6}$/');
});

it('will return an existing encoded object when a duplicate URL is found', function () {
    Links::factory()->create([
        'original' => 'http://example.test',
        'encoded' => 'http://ex.tst/rd6Pn7',
    ]);

    $service = new UrlShortenerService;
    $result = $service->encode('http://example.test');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toMatch('/[A-Za-z0-9]{6}$/');

    assertDatabaseCount('links', 1);
});

it('will save the encoded URL to the database for future use', function () {
    $service = new UrlShortenerService;
    $result = $service->encode('http://example.test');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toMatch('/[A-Za-z0-9]{6}$/');

    assertDatabaseCount('links', 1);
});

it('will save the encoded URL to the cache for future use', function () {
    Cache::shouldReceive('set')->once();

    $service = new UrlShortenerService;
    $result = $service->encode('http://example.test');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toMatch('/[A-Za-z0-9]{6}$/');
});

it('will return a decoded URL from the cache if it exists', function () {
    $links = Links::factory()->make([
        'original' => 'http://example.test',
        'encoded' => 'http://ex.tst/rd6Pn7',
    ]);
    Cache::shouldReceive('get')->once()->andReturn($links);

    $service = new UrlShortenerService;
    $result = $service->decode('http://ex.tst/rd6Pn7');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toEqual('http://example.test');

    assertDatabaseCount('links', 0);
});

it('will return a decoded URL from the database if it exists', function () {
    Links::factory()->create([
        'original' => 'http://example.test',
        'encoded' => 'http://ex.tst/rd6Pn7',
    ]);

    $service = new UrlShortenerService;
    $result = $service->decode('http://ex.tst/rd6Pn7');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toEqual('http://example.test');

    assertDatabaseCount('links', 1);
});

it('will throw an error if a decoded URL cannot be found', function () {
    $service = new UrlShortenerService;
    $result = $service->decode('http://ex.tst/rd6Pn7');

    expect($result)
        ->toBeUrl()
        ->and($result)
        ->toEqual('http://example.test');

    assertDatabaseCount('links', 0);
})->throws(Exception::class);
