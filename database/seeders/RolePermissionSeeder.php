<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            $currentDate = Carbon::now();

            $roles = [
                [
                    'name' => 'superAdmin',
                    'title' => 'مدیر کل سامانه',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'standardUser',
                    'title' => 'کاربر عادی',
                    'created_at' => $currentDate,
                ],
            ];

            foreach ($roles as $role) {
                DB::table('roles')->insert($role);
            }

            $superAdminRole = collect(DB::select("select * from roles where name = 'superAdmin'"))->first();
            $standardUserRole = collect(DB::select("select * from roles where name = 'standardUser'"))->first();

            $user = [
                'first_name' => 'علیرضا',
                'last_name' => 'سلیمی',
                'mobile' => '9194257035',
                'password' => bcrypt('12345678'),
                'role_id' => $superAdminRole->id,
                'mobile_verified_at' => $currentDate,
                'created_at' => $currentDate,
            ];
            $userId = DB::table('users')->insertGetId($user);

            DB::table('profiles')->insert([
                'user_id' => $userId,
                'female' => false,
                'created_at' => $currentDate
            ]);

            $permissions = [
                [
                    'name' => 'site.*',
                    'title' => 'دسترسی کاربران سمت سایت',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => '*',
                    'title' => ' همه دسترسی ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'ads.*',
                    'title' => 'مدیریت آگهی ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'report.*',
                    'title' => 'مدیریت گزارش ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'support.*',
                    'title' => 'مدیریت پیام های پشتیبانی',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'comment.*',
                    'title' => 'مدیریت نظرات',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'blog.*',
                    'title' => 'مدیریت خبر ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'user.*',
                    'title' => 'مدیریت کاربران',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'category.*',
                    'title' => 'مدیریت دسته بندی ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'social.*',
                    'title' => 'مدیریت شبکه های اجتماعی',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'activity.*',
                    'title' => 'مدیریت فعالیت ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'blogCategory.*',
                    'title' => 'مدیریت دسته بندی خبر ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'payment.*',
                    'title' => 'مدیریت پرداخت ها',
                    'created_at' => $currentDate,
                ],
                [
                    'name' => 'message.*',
                    'title' => 'مدیریت پیام ها',
                    'created_at' => $currentDate,
                ],
            ];
            foreach ($permissions as $permission) {
                DB::table('permissions')->insert($permission);
            }

            $allPermission = collect(DB::select("select * from permissions where name = '*'"))->first();
            $standardUserPermission = collect(DB::select("select * from permissions where name = 'site.*'"))->first();

            DB::table('permission_role')->insert([
                'role_id' => $superAdminRole->id,
                'permission_id' => $allPermission->id,
                'created_at' => $currentDate
            ]);

            DB::table('permission_role')->insert([
                'role_id' => $standardUserRole->id,
                'permission_id' => $standardUserPermission->id,
                'created_at' => $currentDate
            ]);

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
        }
    }
}
