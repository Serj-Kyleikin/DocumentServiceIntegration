<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        if(empty(DB::table('orders')->first())) {

            $payload = ['type_id' => 1, 'value' => 50];
            Order::insert($payload);

            $this->command->info('Orders data seeded!');
        } else {
            $this->command->error(('Data already exists'));
        }
    }
}
