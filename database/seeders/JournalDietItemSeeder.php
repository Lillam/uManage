<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Journal\JournalDietItem;

class JournalDietItemSeeder extends Seeder
{
    public function run(): void
    {
        $dietItems = [
            [
                'name' => 'Coke (330ml)',
                'category' => 'drink',
                'calories' => 139,
                'image_url' => ''
            ],
            [
                'name' => 'Monster Energy (500ml)',
                'category' => 'drink',
                'calories' => 220,
                'image_url' => ''
            ]
        ];

        DB::transaction(function() use ($dietItems) {
            $bar = $this->command->getOutput()->createProgressBar(count($dietItems));

            foreach ($dietItems as $dietItem) {
                JournalDietItem::query()->create($dietItem);

                $bar->advance();
            }

            $bar->finish();
        });

        DB::commit();
    }
}
