<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'name'=>'home',
        ]);
        Page::create([
            'name'=>'about',
        ]);
        Page::create([
            'name'=>'approaches',
        ]);
        Page::create([
            'name'=>'blog',
        ]);
        Page::create([
            'name'=>'contact',
        ]);
    }
}