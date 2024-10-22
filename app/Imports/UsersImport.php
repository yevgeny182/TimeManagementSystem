<?php

namespace App\Imports;

use App\Models\User;

class UsersImport
{
    public function createUsers(array $rows)
    {
        foreach ($rows as $row) {
            User::create([
                'last_name' => $row[0],
                'first_name' => $row[1],
                'email' => $row[2],
                'phone_number' => $row[3], // Make sure this matches your User model
            ]);
        }
    }
}
