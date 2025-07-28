<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_project","view_any_project","create_project","update_project","restore_project","restore_any_project","replicate_project","reorder_project","delete_project","delete_any_project","force_delete_project","force_delete_any_project","view_project::status","view_any_project::status","create_project::status","update_project::status","restore_project::status","restore_any_project::status","replicate_project::status","reorder_project::status","delete_project::status","delete_any_project::status","force_delete_project::status","force_delete_any_project::status","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_ticket::comment","view_any_ticket::comment","create_ticket::comment","update_ticket::comment","restore_ticket::comment","restore_any_ticket::comment","replicate_ticket::comment","reorder_ticket::comment","delete_ticket::comment","delete_any_ticket::comment","force_delete_ticket::comment","force_delete_any_ticket::comment","view_ticket::priority","view_any_ticket::priority","create_ticket::priority","update_ticket::priority","restore_ticket::priority","restore_any_ticket::priority","replicate_ticket::priority","reorder_ticket::priority","delete_ticket::priority","delete_any_ticket::priority","force_delete_ticket::priority","force_delete_any_ticket::priority","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_EpicsOverview","page_ProjectBoard","page_TicketTimeline","widget_StatsOverview","widget_TicketsPerProjectChart","widget_UserStatisticsChart","widget_MonthlyTicketTrendChart","widget_ProjectTimeline","widget_RecentActivityTable"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
