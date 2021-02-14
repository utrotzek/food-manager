<?php

it('home')
    ->get('/')
    ->assertStatus(200);
