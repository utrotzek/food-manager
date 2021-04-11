<?php

namespace Database\Seeders\Production;

use App\Repositories\AppStateRepository;
use Illuminate\Database\Seeder;

class AppStateSeeder extends Seeder
{
    protected AppStateRepository $appStateRepository;

    public function __construct(AppStateRepository $appStateRepository)
    {
        $this->appStateRepository = $appStateRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->addDefaultState('MealPlanDisplayRange', 'week');
    }

    protected function addDefaultState(string $name, string $value): void
    {
        if (!$this->appStateRepository->findByName($name)){
            $this->appStateRepository->create([
                'state_name' => $name,
                'state_value' => $value
            ]);
        }
    }
}
