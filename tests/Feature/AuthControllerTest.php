<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, deleteJson, postJson, withToken};

it('allows a user to register themselves using the register endpoint', function () {
    $user = User::factory()->make()->toArray();
    $user['password'] = 'password';

    $response = postJson(route('register'), $user)->assertStatus(201);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeTrue()
        ->and($contents['message'])
        ->toEqual('User Created Successfully');
});

it('will return a 400 Bad Request if validation fails during register', function () {
    $user = User::factory()->make()->toArray();

    $response = postJson(route('register'), $user)->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});

it('allows a user to login themselves using the login endpoint', function () {
    $user = User::factory()->create();
    $login['email'] = $user->email;
    $login['password'] ='password';

    $response = postJson(route('login'), $login)->assertStatus(200);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeTrue()
        ->and($contents['message'])
        ->toEqual('User Logged In Successfully');
});

it('will return a 400 Bad Request if validation fails during login', function () {
    $user = User::factory()->make()->toArray();

    $response = postJson(route('login'), $user)->assertStatus(400);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('validation error');
});

it('will return a 401 Unauthenticated if unknown details are used during login', function () {
    $login['email'] = 'test@test.com';
    $login['password'] ='password';

    $response = postJson(route('login'), $login)->assertStatus(401);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeFalse()
        ->and($contents['message'])
        ->toEqual('Email & Password does not match with our record');
});

it('allows a user to revoke their token using the revokeToken endpoint', function () {
    $user = User::factory()->create();
    $token = $user->createToken("API TOKEN")->plainTextToken;

    $response = withToken($token)->deleteJson(route('revoke'))->assertStatus(200);
    $contents = json_decode($response->getContent(), true);

    expect($contents['status'])
        ->toBeTrue()
        ->and($contents['message'])
        ->toEqual('User Logged Out Successfully');
});

it('will return a 401 Unauthenticated if there is no token to revoke', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->deleteJson(route('revoke'))->assertStatus(401);
    $contents = json_decode($response->getContent(), true);

    expect($contents['message'])
        ->toEqual('Unauthenticated.');
});
