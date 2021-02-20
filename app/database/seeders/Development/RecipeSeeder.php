<?php
namespace Database\Seeders\Development;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        $chiliConCarne = new Recipe([
            'title' => 'Chili con Carne',
            'rating' => 4.5,
            'portion' => 4,
            'comments' => "Could you move it a tad to the left make it pop can it be more retro, so we are a non-profit organization, that will be a conversation piece can it be more retro is there a way we can make the page feel more introductory without being cheesy.\n Can't you just take a picture from the internet? give us a complimentary logo along with the website, can you make the font bigger?\n and the website doesn't have the theme i was going for."
        ]);

        $chiliConCarne->save();

        $gnocchiPan = new Recipe([
            'title' => 'Gnocchi-Pfanne mit Spinat',
            'rating' => 5,
            'portion' => 4,
            'comments' => null
        ]);
        $gnocchiPan->save();
    }
}
