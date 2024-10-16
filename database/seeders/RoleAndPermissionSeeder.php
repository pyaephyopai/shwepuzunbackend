<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);

        $user = Role::create(['name' => 'User']);

        $getRoles = Permission::create(['name' => 'getRoles']);

        $getUserListing = Permission::create(['name' => 'getUserListing']);

        $userStore = Permission::create(['name' => 'userStore']);

        $userUpdate = Permission::create(['name' => 'userUpdate']);

        $userShow = Permission::create(['name' => 'userShow']);

        $userDelete = Permission::create(['name' => 'userDelete']);

        $getBlogs = Permission::create(['name' => 'getBlogs']);

        $blogStore = Permission::create(['name' => 'blogStore']);

        $blogShow = Permission::create(['name' => 'blogShow']);

        $blogUpdate = Permission::create(['name' => 'blogUpdate']);

        $blogDelete = Permission::create(['name' => 'blogDelete']);

        $getCategory = Permission::create(['name' => 'getCategory']);

        $categoryStore = Permission::create(['name' => 'categoryStore']);

        $categoryShow = Permission::create(['name' => 'categoryShow']);

        $categoryUpdate = Permission::create(['name' => 'categoryUpdate']);

        $categoryDelete = Permission::create(['name' => 'categoryDelete']);

        $productList = Permission::create(['name' => 'productList']);

        $productStore = Permission::create(['name' => 'productStore']);

        $productShow = Permission::create(['name' => 'productShow']);

        $productUpdate = Permission::create(['name' => 'productUpdate']);

        $produtDelete = Permission::create(['name' => 'produtDelete']);

        $rating = Permission::create(['name' => 'rating']);

        $orderList = Permission::create(['name' => 'orderList']);

        $orderStore = Permission::create(['name' => 'orderStore']);

        $orderShow = Permission::create(['name' => 'orderShow']);

        $orderUpdate = Permission::create(['name' => 'orderUpdate']);

        $orderDelete = Permission::create(['name' => 'orderDelete']);

        $orderChangeStatus = Permission::create(['name' => 'orderChangeStatus']);

        $userInfoUpdate = Permission::create(['name' => 'userInfoUpdate']);

        $admin->givePermissionTo([
            $getRoles,
            $getUserListing,
            $userStore,
            $userUpdate,
            $userShow,
            $userDelete,
            $getBlogs,
            $blogStore,
            $blogShow,
            $blogUpdate,
            $blogDelete,
            $getCategory,
            $categoryStore,
            $categoryShow,
            $categoryUpdate,
            $categoryDelete,
            $productList,
            $productStore,
            $productShow,
            $productUpdate,
            $produtDelete,
            $rating,
            $orderList,
            $orderStore,
            $orderShow,
            $orderUpdate,
            $orderDelete,
            $orderChangeStatus,
            $userInfoUpdate,

        ]);

        $user->givePermissionTo([
            $userShow,
            $getBlogs,
            $blogShow,
            $rating,
            $orderShow,
            $orderList,
            $userInfoUpdate,
            $orderStore,
        ]);
    }
}
