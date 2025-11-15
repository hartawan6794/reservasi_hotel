<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Team Permissions
        $teamPermissions = [
            ['name' => 'team.menu', 'group_name' => 'Team'],
            ['name' => 'team.all', 'group_name' => 'Team'],
            ['name' => 'team.add', 'group_name' => 'Team'],
            ['name' => 'team.edit', 'group_name' => 'Team'],
            ['name' => 'team.delete', 'group_name' => 'Team'],
        ];

        // Book Area Permissions
        $bookAreaPermissions = [
            ['name' => 'bookarea.menu', 'group_name' => 'Book Area'],
            ['name' => 'update.bookarea', 'group_name' => 'Book Area'],
        ];

        // Create permissions if they don't exist
        foreach ($teamPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['group_name' => $permission['group_name']]
            );
        }

        foreach ($bookAreaPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['group_name' => $permission['group_name']]
            );
        }

        $this->command->info('Team and Book Area permissions created successfully!');
    }
}

