<?php

use App\Models\Unit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('average_gram')->nullable();
            $table->boolean('is_piece')->default(false);
            $table->timestamps();
        });
        $this->addUnits();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }

    protected function addUnits() {
        Unit::create([
            'title' => 'Stück',
            'is_piece' => true
        ]);

        Unit::create([
            'title' => 'Dose',
            'is_piece' => true
        ]);

        Unit::create([
            'title' => 'Gramm',
            'average_gram' => '1'
        ]);

        Unit::create([
            'title' => 'Msp.',
            'average_gram' => '5'
        ]);

        Unit::create([
            'title' => 'El',
            'average_gram' => '20'
        ]);

        Unit::create([
            'title' => 'Tl',
            'average_gram' => '5'
        ]);

        Unit::create([
            'title' => 'L',
            'average_gram' => '1000'
        ]);

        Unit::create([
            'title' => 'ml',
            'average_gram' => '1'
        ]);

        Unit::create([
            'title' => 'Päckchen',
            'average_gram' => '1'
        ]);
    }
}
