<?php


namespace Database\Seeders\Development;

use App\Models\Good;
use App\Models\GoodGroup;
use App\Repositories\GoodGroupRepository;
use Illuminate\Database\Seeder;

class GoodSeeder extends Seeder
{
    public function run(GoodGroupRepository $goodGroupRepository)
    {
        $grainGoods = [];
        $grainGoods[] = new Good([
            'title' => 'Mehl',
            'carbs' => 60,
            'fat' => 45,
            'protein' => 5,
            'kcal' => 200,
            'piece_in_gramm' => 1000
        ]);
        $grainGoods[] = new Good([
            'title' => 'Nudeln',
            'carbs' => 60,
            'fat' => 10,
            'protein' => 10,
            'kcal' => 210,
            'piece_in_gramm' => 500
        ]);
        $grainGoods[] = new Good([
            'title' => 'Reis',
            'carbs' => 60,
            'fat' => 10,
            'protein' => 10,
            'kcal' => 210,
            'piece_in_gramm' => 500
        ]);

        $grainGoods[] = new Good([
            'title' => 'Gnocchis',
            'carbs' => 60,
            'fat' => 10,
            'protein' => 10,
            'kcal' => 210,
            'piece_in_gramm' => 500
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Getreideprodukte'), $grainGoods);

        $fruitAndVeggieGoods = [];
        $fruitAndVeggieGoods[] = new Good([
            'title' => 'Apfel',
            'carbs' => 60,
            'fat' => 5,
            'protein' => 2,
            'kcal' => 90,
            'piece_in_gramm' => 90
        ]);

        $fruitAndVeggieGoods[] = new Good([
            'title' => 'TK Spinat',
            'carbs' => 10,
            'fat' => 10,
            'protein' => 5,
            'kcal' => 50,
            'piece_in_gramm' => 500
        ]);

        $fruitAndVeggieGoods[] = new Good([
            'title' => 'frischer Spinat',
            'carbs' => 10,
            'fat' => 10,
            'protein' => 5,
            'kcal' => 50,
            'piece_in_gramm' => 500
        ]);

        $fruitAndVeggieGoods[] = new Good([
            'title' => 'Knoblauchzehe',
            'carbs' => 10,
            'fat' => 10,
            'protein' => 5,
            'kcal' => 20,
            'piece_in_gramm' => 10
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Obst und Gemüse'), $fruitAndVeggieGoods);

        $meatGoods = [];
        $meatGoods[] = new Good([
            'title' => 'Hähnchenbrust',
            'carbs' => 5,
            'fat' => 5,
            'protein' => 35,
            'kcal' => 210,
            'piece_in_gramm' => 500
        ]);
        $meatGoods[] = new Good([
            'title' => 'Hackfleisch',
            'carbs' => 5,
            'fat' => 20,
            'protein' => 35,
            'kcal' => 210,
            'piece_in_gramm' => 500
        ]);

        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Fleisch'), $meatGoods);

        $fishGoods = [];
        $fishGoods[] = new Good([
            'title' => 'Lachs',
            'carbs' => 5,
            'fat' => 15,
            'protein' => 40,
            'kcal' => 200,
            'piece_in_gramm' => null
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Fisch'), $fishGoods);

        $cheeseGoods = [];
        $cheeseGoods[] = new Good([
            'title' => 'Gouda Scheibenkäse',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);

        $cheeseGoods = [];
        $cheeseGoods[] = new Good([
            'title' => 'fettarmer Frischkäse',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Käse'), $cheeseGoods);

        $tomatoGoods = [];
        $tomatoGoods[] = new Good([
            'title' => 'Tomatenmark',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);

        $tomatoGoods[] = new Good([
            'title' => 'passierte Tomaten',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Tomatenprodukte'), $tomatoGoods);

        $spicesGoods = [];
        $spicesGoods[] = new Good([
            'title' => 'Paprikapulver',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);

        $spicesGoods[] = new Good([
            'title' => 'Bolognese Gewürzmischung',
            'carbs' => 10,
            'fat' => 45,
            'protein' => 10,
            'kcal' => 150,
            'piece_in_gramm' => 20
        ]);
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Gewürze'), $spicesGoods);
    }

    protected function saveGoodsToGroup(GoodGroup $goodGroup, array $goods)
    {
        /** @var Good $good */
        foreach ($goods as $good) {
            $good->goodGroup()->associate($goodGroup);
            $good->save();
        }
    }
}
