<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'subscription-viewAny',
                'description' => 'subscription view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'subscription-view',
                'description' => 'subscription view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'subscription-create',
                'description' => 'subscription create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'subscription-edit',
                'description' => 'subscription edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'subscription-delete',
                'description' => 'subscription delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'kanban-viewAny',
                'description' => 'kanban view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kanban-view',
                'description' => 'kanban view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kanban-create',
                'description' => 'kanban create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kanban-edit',
                'description' => 'kanban edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kanban-delete',
                'description' => 'kanban delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'project-viewAny',
                'description' => 'project view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'project-view',
                'description' => 'project view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'project-create',
                'description' => 'project create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'project-edit',
                'description' => 'project edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'project-delete',
                'description' => 'project delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'supplier-viewAny',
                'description' => 'supplier view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'supplier-view',
                'description' => 'supplier view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'supplier-create',
                'description' => 'supplier create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'supplier-edit',
                'description' => 'supplier edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'supplier-delete',
                'description' => 'supplier delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'customer-viewAny',
                'description' => 'customer view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer-view',
                'description' => 'customer view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer-create',
                'description' => 'customer create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer-edit',
                'description' => 'customer edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer-delete',
                'description' => 'customer delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'invite-viewAny',
                'description' => 'invite view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'invite-view',
                'description' => 'invite view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'invite-create',
                'description' => 'invite create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'invite-edit',
                'description' => 'invite edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'invite-delete',
                'description' => 'invite delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'name' => 'role-viewAny',
                'description' => 'role view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role-view',
                'description' => 'role view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role-create',
                'description' => 'role create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role-edit',
                'description' => 'role edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'role-delete',
                'description' => 'role delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'skill-viewAny',
                'description' => 'skill view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'skill-view',
                'description' => 'skill view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'skill-create',
                'description' => 'skill create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'skill-edit',
                'description' => 'skill edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'skill-delete',
                'description' => 'skill delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'grouping-viewAny',
                'description' => 'grouping view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'grouping-view',
                'description' => 'grouping view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'grouping-create',
                'description' => 'grouping create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'grouping-edit',
                'description' => 'grouping edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'grouping-delete',
                'description' => 'grouping delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'physical-viewAny',
                'description' => 'physical view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'physical-view',
                'description' => 'physical view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'physical-create',
                'description' => 'physical create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'physical-edit',
                'description' => 'physical edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'physical-delete',
                'description' => 'physical delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'juridical-viewAny',
                'description' => 'juridical view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'juridical-view',
                'description' => 'juridical view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'juridical-create',
                'description' => 'juridical create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'juridical-edit',
                'description' => 'juridical edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'juridical-delete',
                'description' => 'juridical delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'checkout-viewAny',
                'description' => 'checkout view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'checkout-view',
                'description' => 'checkout view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'checkout-create',
                'description' => 'checkout create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'checkout-edit',
                'description' => 'checkout edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'checkout-delete',
                'description' => 'checkout delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'account-viewAny',
                'description' => 'account view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'account-view',
                'description' => 'account view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'account-create',
                'description' => 'account create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'account-edit',
                'description' => 'account edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'account-delete',
                'description' => 'account delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'tree-table-viewAny',
                'description' => 'tree-table view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tree-table-view',
                'description' => 'tree-table view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tree-table-create',
                'description' => 'tree-table create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tree-table-edit',
                'description' => 'tree-table edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tree-table-delete',
                'description' => 'tree-table delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ticket-viewAny',
                'description' => 'ticket view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ticket-view',
                'description' => 'ticket view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ticket-create',
                'description' => 'ticket create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ticket-edit',
                'description' => 'ticket edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ticket-delete',
                'description' => 'ticket delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'contributor-viewAny',
                'description' => 'contributor view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contributor-view',
                'description' => 'contributor view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contributor-create',
                'description' => 'contributor create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contributor-edit',
                'description' => 'contributor edit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contributor-delete',
                'description' => 'contributor delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];
        foreach ($permissions as $permission) {
            Permission::insert(
                $permission
            );
        }

    }
}
