<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitiesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        if(empty(DB::table('entities')->first())) {

            $payload = ['type_id' => 1, 'amount' => 20, 'value' => 50, 'order_id' => 1];
            Entity::insert($payload);

            $this->command->info('Entities data seeded!');
        } else {
            $this->command->error(('Data already exists'));
        }
    }
}
