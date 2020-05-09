<?php

use App\Admin\Permissions\UserRoles;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $superAdmin */
        /** @var User $employee */
        /** @var User $subscriber */

        $environment = App::environment();
        if (strtolower($environment) === 'production') {
            exit("Cannot seed users in a production environment.\n");
        }

        $superAdmin = User::create(
            [
                User::NAME => config('seeder.super_admin.name', 'Default SuperAdmin'),
                User::EMAIL => config('seeder.super_admin.email', 'superadmin@example.com'),
                User::PASSWORD => Hash::make(config('seeder.super_admin.password', 'password')),
            ]
        );
        $superAdmin->assignRole(UserRoles::SUPER_ADMIN);

        $subscriber = User::create(
            [
                User::NAME => config('seeder.subscriber.name', 'Default Subscriber'),
                User::EMAIL => config('seeder.subscriber.email', 'subscriber@example.com'),
                User::PASSWORD => Hash::make(config('seeder.subscriber.password', 'password')),
            ]
        );
        $subscriber->assignRole(UserRoles::SUBSCRIBER);

        $employee = User::create(
            [
                User::NAME => config('seeder.employee.name', 'Default Employee'),
                User::EMAIL => config('seeder.employee.email', 'employee@example.com'),
                User::PASSWORD => Hash::make(config('seeder.employee.password', 'password')),
            ]
        );
        $employee->assignRole(UserRoles::EMPLOYEE);
    }
}
