<?php
it('accepts ratings with comma and decimal point', function () {
    $subject = new \App\Models\Recipe();
    $subject['rating'] = '4,5';
    expect($subject->rating)->toBe(4.5);

    $subject['rating'] = '6,5';
    expect($subject->rating)->toBe(6.5);
});
