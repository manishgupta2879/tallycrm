<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
                'status' => $data['status'] ?? false,
            ]);

            if (isset($data['companies'])) {
                $user->companies()->attach($data['companies']);
            }

            return $user;
        });
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'status' => $data['status'] ?? false,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (isset($data['companies'])) {
                $user->companies()->sync($data['companies']);
            }

            return $user;
        });
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool|null
     * @throws \Exception
     */
    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            return $user->delete();
        });
    }
}
