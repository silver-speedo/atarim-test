<?php

use App\Contracts\UrlShortenerContract;
use App\Models\User;

use Mockery\MockInterface;

use function Pest\Laravel\{withToken};

it('allows a user to encode a url using the encode endpoint', function () {
    $this->mock(UrlShortenerContract::class, function (MockInterface $mock) {
        $mock->shouldReceive('encode')->once()->andReturn('http://ex.tst/a41SdY');
    });

    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('encode'), [
        'url' => 'http://example.test'
    ])->assertStatus(200);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeTrue()
        ->and($contents['message'])
        ->toBeUrl();
});

it('will return a 400 Bad Request if validation fails during encoding', function () {
    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('encode'), [
        'url' => ''
    ])->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});

it('will return a 400 Bad Request if a valid URL is not used during encoding', function () {
    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('encode'), [
        'url' => 'httpx://example.test'
    ])->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});

it('allows a user to encode a url using the decode endpoint', function () {
    $this->mock(UrlShortenerContract::class, function (MockInterface $mock) {
        $mock->shouldReceive('decode')->once()->andReturn('http://example.test');
    });

    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('decode'), [
        'url' => 'http://ex.tst/a41SdY'
    ])->assertStatus(200);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeTrue()
        ->and($contents['message'])
        ->toBeUrl();
});

it('will return a 400 Bad Request if validation fails during decoding', function () {
    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('decode'), [
        'url' => ''
    ])->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});

it('will return a 400 Bad Request if a valid URL is not used during decoding', function () {
    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->postJson(route('encode'), [
        'url' => 'httpx://ex.tst/a41SdY'
    ])->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});
