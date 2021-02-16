<?php
it('basic test', function () {
    expect(true)->toBe(true);
});


it('can calculate', function () {
    $myClass = new \App\MyClass();
    expect($myClass->calc())->toBe(2);
});
