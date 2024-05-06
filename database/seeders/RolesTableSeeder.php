<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 'PRODUCT_MANAGER', 'name' => 'Product Manager (Vice)'],
            ['id' => 'UI_UX', 'name' => 'UI/UX'],
            ['id' => 'FRONTEND', 'name' => 'Frontend'],
            ['id' => 'BACKEND', 'name' => 'Backend'],
            ['id' => 'BACKEND_BACKUP', 'name' => 'Backend Backup'],
            ['id' => 'WORDPRESS_TECHNICAL', 'name' => 'Wordpress Technical'],
            ['id' => 'GRAPHIC_DESIGNER', 'name' => 'Graphic Designer'],
            ['id' => 'QC_COMPLAIN', 'name' => 'QC & Complain'],
            ['id' => 'OFFPAGE_RESEARCH', 'name' => 'Offpage research'],
            ['id' => 'COMPETITOR_RESEARCH', 'name' => 'Competitor Research'],
            ['id' => 'INPAGE_SEO', 'name' => 'Inpage SEO'],
            ['id' => 'CONTENT_1', 'name' => 'Content 1 (Lead)'],
            ['id' => 'CONTENT_2', 'name' => 'Content 2'],
            ['id' => 'CONTENT_3', 'name' => 'Content 3'],
            ['id' => 'CONTENT_4', 'name' => 'Content 4'],
            ['id' => 'CONTENT_5', 'name' => 'Content 5'],
            ['id' => 'FAKE_COMMENT', 'name' => 'Fake Comment (Web & Social)'],
            ['id' => 'SOCIAL_MEDIA', 'name' => 'Social Media'],
        ];
        Role::insert($roles);
    }
}
