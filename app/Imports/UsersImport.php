<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'last_name' => $row[0],
            'first_name' => $row[1],
            'email' => $row[2],
            'phone' => $row[3],
        ]);
    }
}
