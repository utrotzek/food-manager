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
            'title' => 'Spinat',
            'carbs' => 10,
            'fat' => 10,
            'protein' => 5,
            'kcal' => 50,
            'piece_in_gramm' => 500
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
        $this->saveGoodsToGroup($goodGroupRepository->findByTitle('Käse'), $cheeseGoods);
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
