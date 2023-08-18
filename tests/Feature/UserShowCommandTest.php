<?php


use Laravel\Forge\Resources\User;

it('displays the current user', function () {
    $this->client->shouldReceive('user')->andReturn(new User([
        'id' => 0,
        'name' => 'John Do',
        'email' => 'john@do.com',
        'cardLastFour' => '4444',
        'connectedToGithub' => true,
        'connectedToGitlab' => true,
        'connectedToBitbucket' => true,
        'connectedToBitbucketTwo' => true,
        'connectedToDigitalocean' => true,
        'connectedToLinode' => true,
        'connectedToVultr' => true,
        'connectedToAws' => true,
        'readyForBilling' => true,
        'stripeIsActive' => 1,
        'stripePlan' => 'yearly-basic-199-trial',
        'subscribed' => 1,
        'canCreateServers' => true,
    ]));


    $this
        ->artisan('user:show')
        ->expectsTable(
            [
                '   ID',
                '   Email',
                '   Name',
                '   Github',
                '   Gitlab',
                '   Bitbucket',
                '   DO',
                '   AWS',
                '   Linode',
                '   Vultr',
                '   Can create server ?'
            ],
            [
                [
                    'id' => '   0',
                    'email' => '   john@do.com',
                    'name' => '   John Do',
                    'connectedToGithub' => '   Yes',
                    'connectedToGitlab' => '   Yes',
                    'connectedToBitbucket' => '   Yes',
                    'connectedToDigitalocean' => '   Yes',
                    'connectedToLinode' => '   Yes',
                    'connectedToVultr' => '   Yes',
                    'connectedToAws' => '   Yes',
                    'canCreateServers' => '   Yes',
                ],
            ],
            'compact'
        );


});

