<?php namespace Mascame\Artificer;


use Illuminate\Database\Migrations\Migration;

class ArtificerMigration extends Migration
{

    public function __construct()
    {
        $this->connection = config('admin.extension_drivers.database.connection');
    }

}