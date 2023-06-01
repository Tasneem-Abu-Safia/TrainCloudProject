<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public function run()
    {
        $fields = [
            'Software Development',
            'Web Development',
            'Mobile App Development',
            'Database Administration',
            'IT Project Management',
            'Data Analysis',
            'Machine Learning',
            'DevOps',
            'IT Support'
        ];

        foreach ($fields as $field) {
            Field::create(['name' => $field]);
        }
    }

}
