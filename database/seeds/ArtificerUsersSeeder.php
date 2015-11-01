<?php

use Illuminate\Database\Seeder;

class ArtificerUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \Mascame\Artificer\Model\FakeModel();

        $model->setup([
            'table' => 'artificer_users'
        ]);

        $model->unguard();

        $model->create([
            'name' => 'Demo User',
            'email' => 'artificer@artificer.at', // fake email
            'username' => 'artificer',
            'password' => \Hash::make('artificer'),
            'role' => 'admin',
        ]);
    }
}
