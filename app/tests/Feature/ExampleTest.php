<?php
use function Pest\Laravel\get;

it('Basic test', function () {
    get('/')->assertStatus(200);
});
