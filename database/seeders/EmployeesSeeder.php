<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'firstName'  => 'John',
            'middleName' => 'Michael',
            'lastName'   => 'Doe',
            'employeeID' => 'EMP001',
        ]);

        Employee::create([
            'firstName'  => 'Jane',
            'middleName' => 'Ann',
            'lastName'   => 'Smith',
            'employeeID' => 'EMP002',
        ]);

        Employee::create([
            'firstName'  => 'David',
            'middleName' => 'Robert',
            'lastName'   => 'Johnson',
            'employeeID' => 'EMP003',
        ]);

        Employee::create([
            'firstName'  => 'Alice',
            'middleName' => 'Grace',
            'lastName'   => 'Williams',
            'employeeID' => 'EMP004',
        ]);

        Employee::create([
            'firstName'  => 'Peter',
            'middleName' => 'James',
            'lastName'   => 'Brown',
            'employeeID' => 'EMP005',
        ]);
    }
}
