<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OperatorList;

class OperatorSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OperatorList::create([
            'name' => 'V!',
            'short_code' => 'vi',
            'type' => 'mobile_recharge',
            'commission_type' => 'percentage',
            'commission_value' => '2',
            'status' => 'active',
        ]);
        OperatorList::create([
            'name' => 'Airtel',
            'short_code' => 'airtel',
            'type' => 'mobile_recharge',
            'commission_type' => 'percentage',
            'commission_value' => '2',
            'status' => 'active',
        ]);
        OperatorList::create([
            'name' => 'BSNL',
            'short_code' => 'bsnl',
            'type' => 'mobile_recharge',
            'commission_type' => 'percentage',
            'commission_value' => '2',
            'status' => 'active',
        ]);
        OperatorList::create([
            'name' => 'Jio',
            'short_code' => 'jio',
            'type' => 'mobile_recharge',
            'commission_type' => 'percentage',
            'commission_value' => '2',
            'status' => 'active',
        ]);
    }
}
