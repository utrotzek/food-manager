<?php
namespace Database\Seeders\Development;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        Tag::create(['title' => 'Fast food']);
        Tag::create(['title' => 'Diät']);
        Tag::create(['title' => 'IQs Kitchen']);
        Tag::create(['title' => 'Weight Watcher']);
    }
}
