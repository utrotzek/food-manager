<?php
namespace Database\Seeders\Development;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Step;
use App\Repositories\GoodRepository;
use App\Repositories\IngredientCategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UnitRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RecipeSeeder extends Seeder
{
    protected TagRepository $tagRepository;
    protected GoodRepository $goodRepository;
    protected UnitRepository $unitRepository;
    protected IngredientCategoryRepository $ingredientCategoryRepository;

    public function run(
        TagRepository $tagRepository,
        GoodRepository $goodRepository,
        UnitRepository $unitRepository,
        IngredientCategoryRepository $ingredientCategoryRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->goodRepository = $goodRepository;
        $this->unitRepository = $unitRepository;
        $this->ingredientCategoryRepository = $ingredientCategoryRepository;

        $chiliConCarne = new Recipe([
            'title' => 'Chili con Carne',
            'rating' => 4.5,
            'image' => '1.jpg',
            'portion' => 4,
            'comments' => "Could you move it a tad to the left make it pop can it be more retro, so we are a non-profit organization, that will be a conversation piece can it be more retro is there a way we can make the page feel more introductory without being cheesy.\n Can't you just take a picture from the internet? give us a complimentary logo along with the website, can you make the font bigger?\n and the website doesn't have the theme i was going for."
        ]);
        $chiliConCarne->save();
        $this->addRelations(
            $chiliConCarne,
            [
                'Zwiebeln kleinwürfeln, Bohnen und Mais abtropfen lassen',
                'Hackfleisch anbraten. Dabei immer umrühren, sodass nichts am Topfboden anbrennt. Zwischendurch immer wieder mit Gemüsebrühe ablöschen und den Boden freikratzen. Der Boden muss blank bleiben.',
                'Zwiebeln hinzugeben und glasig andünsten.',
                'Tomatenmark hinzufügen und anschwitzen. Dann gewürze hinzugeben und scharf anbraten. Wir wollen an die ätherischen Öle.',
                'Anschließend mit der restlichen Gemüsebrühe ablöschen und die passierten Tomaten hinzugeben.',
                'Die Zimtstangen hinzugeben und den Eintopf bei geringer Hitze 45 Minuten lang köcheln lassen. Zwischndurch umrühren.',
                'Als letztes die Bohnen und ggf. weiteres Gemüse hinzugeben und weitere 30 Minuten köcheln lassen.',
                'Zimtstangen entfernen und servieren. Guten Apetit!'
            ],
            [
                'Eintopf'
            ],
            [
                $this->createIngredient('Hackfleisch', 'Gramm', 500, $chiliConCarne),
                $this->createIngredient('Tomatenmark', 'Gramm', 200, $chiliConCarne ),
                $this->createIngredient('Zimtstange', 'Stück', 2, $chiliConCarne ),
                $this->createIngredient('Kreuzkümmel', 'Tl', 2, $chiliConCarne ),
                $this->createIngredient('passierte Tomaten', 'Gramm', 500, $chiliConCarne ),
                $this->createIngredient('Kidneybohnen a.d. Dose', 'Gramm', 200, $chiliConCarne ),
                $this->createIngredient('Brechbohneen a.d. Glas', 'Gramm', 200, $chiliConCarne ),
                $this->createIngredient('Mais a.d. Dose', 'Dose', 1, $chiliConCarne ),
                $this->createIngredient('Paprikapulver', 'El', 2, $chiliConCarne ),
            ]
        );
        Storage::disk('public')->put('recipe-images/1.jpg', file_get_contents(__DIR__.'/../../../resources/images/fixture-images/chili-con-carne.jpg'));

        $gnocchiPan = new Recipe([
            'title' => 'Gnocchi-Pfanne mit Spinat',
            'rating' => 3,
            'image' => '2.png',
            'portion' => 4,
            'comments' => null
        ]);
        $gnocchiPan->save();
        $this->addRelations(
            $gnocchiPan,
            [
                'Cocktailtomaten halbieren',
                'Knoblauch fein hacken',
                'Coktailtomaten anbraten',
                'Knoblauch hinzugeben',
                'frischen Spinat hinzugeben',
                'stückige Tomaten hinzufügen'
            ],
            [
                'IQs Kitchen',
                'Diät'
            ],
            [
                $this->createIngredient('Gnocchis', 'Gramm', 500, $gnocchiPan),
                $this->createIngredient('frischer Spinat', 'Gramm', 500, $gnocchiPan),
                $this->createIngredient('fettarmer Frischkäse', 'El', 2, $gnocchiPan)
            ]
        );
        Storage::disk('public')->put('recipe-images/2.png', file_get_contents(__DIR__.'/../../../resources/images/fixture-images/gnocchi-pfanne-mit-spinat.png'));

        $pizza = new Recipe([
            'title' => 'Pizza mit Schinken',
            'rating' => 5,
            'image' => '3.jpg',
            'portion' => 4,
            'comments' => 'Die Pizza ist mega lecker und einfach herzustellen zugleich'
        ]);
        $pizza->save();
        $this->addRelations(
            $pizza,
            [
                'Den Backofen auf maximale Temperatur vorheiten. Wenn vorhanden einen Pizzastein verwenden.',
                'Mehr in eine Schüssel geben',
                'Trockenhefe und Salz hinzugeben. Dann lauwarmes Wasser hinzugeben. Mit dem Wasser aufpassen, dass der Teig nicht zu klebrig wird.',
                'Den Teig kneten.',
                '45. min - 1 Stunde gehen lassen.',
                'Ausrollen und mit Pizzasauce bestreichen',
                'Mit Schinken belegen und dann den geriebenen Käse drüber streuen',
                'Ab in den Offen für ca. 8 - 10 min.',
                'Rausholen, vierteln und genießen'
            ],
            [
                'IQs Kitchen',
                'Fast Food'
            ],
            [
                $this->createIngredient('Dinkelmehl', 'Gramm', 200, $pizza, 'für den Teig'),
                $this->createIngredient('Trockenhefe', 'Päckchen', 1, $pizza, 'für den Teig'),
                $this->createIngredient('Pizzasauce', 'Gramm', 200, $pizza, 'für den Belag'),
                $this->createIngredient('Schinken', 'Gramm', 100, $pizza, 'für den Belag'),
                $this->createIngredient('Raspelkäse', 'Gramm', 100, $pizza, 'für den Belag'),
                $this->createIngredient('Oregano', 'Tl', 1, $pizza, 'für den Belag'),
            ]
        );
        Storage::disk('public')->put('recipe-images/3.jpg', file_get_contents(__DIR__.'/../../../resources/images/fixture-images/pizza.jpg'));
    }

    protected function addRelations(Recipe $recipe, array $stepDescriptions, array $tagNames, array $ingredients): void
    {
        $steps = [];
        $i = 10;
        foreach ($stepDescriptions as $description) {
            $steps[] = new Step([
                'description' => $description,
                'sort' => $i
            ]);
            $i += 10;
        }
        $recipe->steps()->saveMany($steps);

        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[] = $this->tagRepository->findByTitle($tagName);
        }
        $recipe->tags()->saveMany($tags);
        $recipe->ingredients()->saveMany($ingredients);
    }

    protected function createIngredient(string $goodTitle, string $unitTitle, int $amount, Recipe $recipe, string $categoryTitle = null)
    {
        $category = null;
        if ($categoryTitle){
            $category = $this->ingredientCategoryRepository->findByRecipeAndTitle($recipe, $categoryTitle);

            if (!$category){
                $category = $this->ingredientCategoryRepository->create($categoryTitle, $recipe);
            }
        }
        $good = $this->goodRepository->findByIdOrSlug($goodTitle);
        $unit = $this->unitRepository->findByIdOrSlug($unitTitle);

        $ingredient = new Ingredient(['unit_amount' => $amount]);
        $ingredient->good()->associate($good);
        $ingredient->unit()->associate($unit);
        if ($category){
            $ingredient->category()->associate($category);
        }
        return $ingredient;
    }
}
