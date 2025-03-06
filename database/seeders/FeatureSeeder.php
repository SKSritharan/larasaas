<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['name' => 'Products'],
            ['name' => 'Subscribers'],
            ['name' => 'Advanced analytics'],
            ['name' => 'Database Backup'],
            ['name' => '24X7 Support'],
            ['name' => 'Custom domain'],
            ['name' => 'Custom branding'],
            ['name' => 'API access'],
            ['name' => 'Custom reports'],
            ['name' => 'Custom permissions'],
            ['name' => 'Custom integrations'],
        ];

        foreach ($features as $feature) {
            Feature::create(['name' => $feature['name']]);
        }
    }
}
