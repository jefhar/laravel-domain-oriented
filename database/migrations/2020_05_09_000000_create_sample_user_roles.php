<?php

use App\Admin\Permissions\UserPermissions;
use App\Admin\Permissions\UserRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSampleUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adjust these Roles as needed.
        $employee = Role::create(['name' => UserRoles::EMPLOYEE]);
        $subscriber = Role::create(['name' => UserRoles::SUBSCRIBER]);
        $superAdmin = Role::create(['name' => UserRoles::SUPER_ADMIN]);

        // Add permissions as needed.
        $employeePermission = Permission::create(['name'=> UserPermissions::VIEW_EMPLOYEES]);
        $subscriberPermission = Permission::create(['name'=> UserPermissions::CAN_SUBSCRIBE]);

        // Apply permissions to roles.
        $employee->givePermissionTo(
            $employeePermission
        );

        $subscriber->givePermissionTo(
            $subscriberPermission
        );

        $superAdmin->givePermissionTo(
            Permission::all()
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sample_user_roles');
    }
}
