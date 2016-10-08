<?php

namespace Mascame\Artificer\Commands;

use Illuminate\Database\Console\Migrations\RefreshCommand;

class MigrateRefreshCommandExtension extends RefreshCommand
{
    public function call($command, array $arguments = [])
    {
        $command = 'artificer:'.$command;

        return parent::call($command, $arguments);
    }
}
