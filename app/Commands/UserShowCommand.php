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

        $this->table([
            'ID', 'Email', 'Name', 'Github', 'Gitlab', 'Bitbucket', 'DO', 'AWS', 'Linode', 'Vultr', 'Can create server ?'
        ], collect([[
            $user->id,
            $user->email,
            $user->name,
            $user->connectedToGithub ? 'Yes' : 'No',
            $user->connectedToGitlab ? 'Yes' : 'No',
            $user->connectedToBitbucket ? 'Yes' : 'No',
            $user->connectedToDigitalocean ? 'Yes' : 'No',
            $user->connectedToAws ? 'Yes' : 'No',
            $user->connectedToLinode ? 'Yes' : 'No',
            $user->connectedToVultr ? 'Yes' : 'No',
            $user->canCreateServers ? 'Yes' : 'No'
        ]])->all());
    }
}
