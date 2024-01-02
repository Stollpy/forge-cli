<?php

namespace App\Commands;

use Laravel\Forge\Resources\User;

class UserShowCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'user:show';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Retrieving the current user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->step('Retrieving the current user');

        $user = $this->forge->user();

        $serverConnections = collect(['Github', 'Gitlab', 'Bitbucket', 'Digitalocean', 'Aws', 'Linode', 'Vultr']);

        $this->table([
            'ID', 'Email', 'Name', ...$serverConnections, 'Can create server ?'
        ], collect([[
            $user->id,
            $user->email,
            $user->name,
            ...$serverConnections->map(fn ($connection) => $user->{"connectedTo$connection"} ? 'Yes' : 'No'),
            $user->canCreateServers ? 'Yes' : 'No'
        ]])->all());
    }
}
