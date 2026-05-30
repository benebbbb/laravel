<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $medicines = [
            // 5 expiring soon (within 7 days from today)
            ['Amoxicillin',      'Antibiotic for bacterial infections',      'Capsule',   30, now()->addDays(2)->format('Y-m-d')],
            ['Paracetamol',      'Pain reliever and fever reducer',          'Tablet',    50, now()->addDays(4)->format('Y-m-d')],
            ['Salbutamol',       'Bronchodilator for asthma relief',         'Injection', 10, now()->addDays(1)->format('Y-m-d')],
            ['Betamethasone',    'Topical steroid for skin inflammation',    'Ointment',   8, now()->addDays(6)->format('Y-m-d')],
            ['Dextromethorphan', 'Cough suppressant',                        'Syrup',     20, now()->addDays(3)->format('Y-m-d')],
            // 5 not expiring soon (more than 7 days)
            ['Metformin',        'Oral diabetes medication',                 'Tablet',    60, '2026-09-05'],
            ['Clotrimazole',     'Antifungal ear and skin treatment',        'Drops',     12, '2026-07-14'],
            ['Vitamin C 500mg',  'Immune system support supplement',        'Capsule',   90, '2027-01-01'],
            ['Hydrocortisone',   'Anti-inflammatory topical cream',          'Ointment',   6, '2026-11-25'],
            ['Losartan',         'Blood pressure medication',                'Tablet',    45, '2026-08-19'],
        ];

        foreach ($medicines as [$name, $desc, $category, $qty, $exp]) {
            \App\Models\Medicine::create([
                'medicine_name'   => $name,
                'description'     => $desc,
                'category'        => $category,
                'quantity'        => $qty,
                'expiration_date' => $exp,
                'created_by'      => $user->id,
            ]);
        }
    }
}
