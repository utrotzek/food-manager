<?php
it('accepts unit_amounts with comma and decimal point', function () {
    $subject = new \App\Models\Ingredient();
    $subject['unit_amount'] = '4,5';
    expect($subject->unit_amount)->toBe(4.5);

    $subject['unit_amount'] = '6,5';
    expect($subject->unit_amount)->toBe(6.5);
});
