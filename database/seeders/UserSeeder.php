<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $this->disableForeignKeys();
        $this->truncate('users');
        //$users = \App\Models\User::factory(10)->create();
        $user = \App\Models\User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com'
        ]);
        $this->truncate('listings');
        \App\Models\Listing::factory(6)->create([
            'user_id' => $user->id
        ]);
        $this->enableForeignKeys();

    }
}
