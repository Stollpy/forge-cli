<?php
use Laravel\Forge\Resources\Server;

it("store a server", function () {
    $this->client->shouldReceive('server')->andReturn(new Server([
                "id" => 324,
                "name" => "Server stored",
                "type" => "web",
                "credentialId" => 432,
                "size" => "5Go",
                "region" => "Toronto",
                "ipAddress" => "127.0.0.1",
                "privateIpAddress" => "127.0.0.1",
                "phpVersion" => "php82",
                "blackfireStatus" => "None",
                "papertrailStatus" => "None",
                "isReady" => true,
                "revoked" => false,
                "createdAt" => "08-26-2023",
                "sudoPassword" => "1234",
                "databasePassword" => "1234",
                "provisionCommand" => ""
            ]))
    ;

    $this
        ->artisan('server:store')
        ->expectsTable(
            [
                "   ID",
                "   Name",
                "   Type",
                "   Size",
                "   Region",
                "   Ip Address",
                "   Is Ready",
                "   Sudo Password",
                "   Database Password",
                "   Created At"
            ],
            [
                [
                    "id" => "   324",
                    "name" => "   Server stored",
                    "type" => "   web",
                    "size" => "   5Go",
                    "region" => "   Toronto",
                    "ipAddress" => "   127.0.0.1",
                    "phpVersion" => "   php82",
                    "isReady" => "   Yes",
                    "sudoPassword" => "   1234",
                    "databasePassword" => "   1234",
                    "createdAt" => "   08-26-2023",
                ],
            ],
            'compact'
        );
});
