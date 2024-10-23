<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'last_name' => $row[0],
            'first_name' => $row[1],
            'email' => $row[2],
            'phone_number' => $row[3],
        ]);
    }
}
