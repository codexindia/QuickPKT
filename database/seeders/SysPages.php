<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemPages;
class SysPages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $new = new SystemPages;
        $new->page_title = 'privacy_policy';
        $new->page_content = '';
        $new->page_slug = 'privacy_policy';

    }
}
