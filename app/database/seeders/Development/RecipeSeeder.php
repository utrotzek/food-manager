<?php
namespace Database\Seeders\Development;

use App\Models\Recipe;
use App\Models\Step;
 use App\Repositories\TagRepository;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    protected TagRepository $tagRepository;

    public function run(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;

        $chiliConCarne = new Recipe([
            'title' => 'Chili con Carne',
            'rating' => 4.5,
            'portion' => 4,
            'comments' => "Could you move it a tad to the left make it pop can it be more retro, so we are a non-profit organization, that will be a conversation piece can it be more retro is there a way we can make the page feel more introductory without being cheesy.\n Can't you just take a picture from the internet? give us a complimentary logo along with the website, can you make the font bigger?\n and the website doesn't have the theme i was going for."
        ]);
        $chiliConCarne->save();
        $this->addRelations(
            $chiliConCarne,
            [
            'Hackfleisch anbraten. Immer umrühren, sodass nichts am Topfboden anbrennt',
            'Zwiebeln glasig andünsten',
            'Tomatenmark hinzufügen und anschwitzen',
            'Gewürze hinzufügen'
            ],
            [
                'Eintopf'
            ]
        );

        $gnocchiPan = new Recipe([
            'title' => 'Gnocchi-Pfanne mit Spinat',
            'rating' => 5,
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
            ]
        );
    }

    protected function addRelations(Recipe $recipe, array $stepDescriptions, array $tagNames): void
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
    }
}
