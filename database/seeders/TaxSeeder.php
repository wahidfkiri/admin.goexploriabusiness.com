<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    public function run()
    {
        $taxes = [
            ['name' => 'TVA 20%', 'code' => 'TVA20', 'rate' => 20.00, 'type' => 'tva', 'is_default' => true],
            ['name' => 'TVA 10%', 'code' => 'TVA10', 'rate' => 10.00, 'type' => 'tva'],
            ['name' => 'TVA 5.5%', 'code' => 'TVA55', 'rate' => 5.50, 'type' => 'tva'],
            ['name' => 'TPS 5%', 'code' => 'TPS5', 'rate' => 5.00, 'type' => 'tps'],
            ['name' => 'TVQ 9.975%', 'code' => 'TVQ9975', 'rate' => 9.975, 'type' => 'tvq'],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }
    }
}