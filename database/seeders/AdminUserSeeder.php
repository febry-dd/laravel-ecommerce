<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.email');

        if (! is_string($email) || $email === '') {
            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new RuntimeException('Register the configured ADMIN_EMAIL account before running this seeder.');
        }

        $user->forceFill(['is_admin' => true])->save();
    }
}
