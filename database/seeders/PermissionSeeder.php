<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions_admin = [


            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            'user-table',
            'user-add',
            'user-edit',
            'user-delete',
    
            'celebrity-table',
            'celebrity-add',
            'celebrity-edit',
            'celebrity-delete',

            'shop-table',
            'shop-add',
            'shop-edit',
            'shop-delete',

            'brand-table',
            'brand-add',
            'brand-edit',
            'brand-delete',


            'order-table',
            'order-add',
            'order-edit',
            'order-delete',


            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            'category-table',
            'category-add',
            'category-edit',
            'category-delete',


            'product-table',
            'product-add',
            'product-edit',
            'product-delete',

            'coupon-table',
            'coupon-add',
            'coupon-edit',
            'coupon-delete',



            'wallet-table',
            'wallet-add',
            'wallet-edit',
            'wallet-delete',

            'noteVoucher-table',
            'noteVoucher-add',
            'noteVoucher-edit',
            'noteVoucher-delete',

        ];

         foreach ($permissions_admin as $permission_ad) {
            Permission::create(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}
