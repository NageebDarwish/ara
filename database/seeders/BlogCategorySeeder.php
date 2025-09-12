<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCategory::create(['name'=>'travel']);
        BlogCategory::create(['name'=>'culture']);
        BlogCategory::create(['name'=>'food']);
        BlogCategory::create(['name'=>'places']);
        BlogCategory::create(['name'=>'history']);
    }
}