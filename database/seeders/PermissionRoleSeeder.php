<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],//1
            ['name' => 'User'],//2

        ];
        $permissions = [
            ['name' => 'addCountry'],//1
            ['name' => 'updateCountry'],//2
            ['name' => 'deleteCountry'],//3
            ['name' => 'country_updatePhoto'],//4
            ['name' => 'addCity'],//5
            ['name' => 'updateCity'],//6
            ['name' => 'deleteCity'],//7
            ['name' => 'city_updatePhoto'],//7
            ['name' => 'addFacility'],//8
            ['name' => 'updateFacility'],//9
            ['name' => 'deleteFacility'],//10
            ['name' => 'facility_updatePhoto'],//10

            ['name' => 'addHotel'],//11
            ['name' => 'updateHotel'],//12
            ['name' => 'deleteHotel'],//13
            ['name' => 'hotel_updatePhoto'],//10

            ['name' => 'addRestaurant'],//14
            ['name' => 'updateRestaurant'],//15
            ['name' => 'deleteRestaurant'],//16
            ['name' => 'restaurant_updatePhoto'],//10

            ['name' => 'addCompany'],//17
            ['name' => 'updateCompany'],//18
            ['name' => 'deleteCompany'],//19
            ['name' => 'company_updatePhoto'],//10
            ['name' => 'getTripBook'],//10


        ];
        Role::insert($roles);
        Permission::insert($permissions);


        PermissionRole::insert([
            'permission_id' => 1,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 2,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 3,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 4,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 5,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 6,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 7,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 8,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 9,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 10,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 11,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 12,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 13,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 14,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 15,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 16,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 17,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 18,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 19,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 20,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 21,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 22,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 23,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 24,
            'role_id' => 1,
        ]);
        PermissionRole::insert([
            'permission_id' => 25,
            'role_id' => 1,
        ]);


    }
}
