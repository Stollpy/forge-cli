<?php

namespace App\Commands;

use App\Commands\Concerns\InteractsWithValuesFile;
use App\Factories\Rules\ServerStoreRulesFactory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Laravel\Forge\Exceptions\TimeoutException;
use Laravel\Forge\Resources\Server;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ServerStoreCommand extends Command
{
    use InteractsWithValuesFile;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:store
        {--provider=null : The server provider. Valid values are ocean2 for Digital Ocean, linode4, vultr2, aws, hetzner and custom.}
        {--ubuntu_version=22.04 : The version of Ubuntu to create the server with. "22.04" is used by default}
        {--type=app : The type of server to create. Valid values are app, web, loadbalancer, cache, database, worker, meilisearch.}
        {--credential_id=null : This is only required when the provider is not custom.}
        {--region=null : The name of the region where the server will be created. This value is not required you are building a Custom VPS server.}
        {--ip_address=null : The IP Address of the server. Only required when the provider is custom.}
        {--private_ip_address=null : The Private IP Address of the server. Only required when the provider is custom.}
        {--php_version=null : Valid values are php82, php81, php80, php74, php73,php72, php70, and php56.}
        {--database=null : The name of the database Forge should create when building the server. If omitted, forge will be used.}
        {--database_type=null : Valid values are mysql8, mariadb106, mariadb1011, postgres, postgres13, postgres14 or postgres15.}
        {--network=null : An array of server IDs that the server should be able to connect to.}
        {--recipe_id=null : An optional ID of a recipe to run after provisioning.}
        {--aws_vpc_id=null : ID of the existing VPC.}
        {--aws_subnet_id=null : ID of the existing subnet.}
        {--aws_vpc_name=null : When creating a new one.}
        {--hetzner_network_id=null : ID of the existing VPC.}
        {--ocean2_vpc_uuid=null : UUID of the existing VPC.}
        {--ocean2_vpc_name=null : When creating a new one.}
        {--vultr2_network_id=null : ID of the existing private network.}
        {--vultr2_network_name=null : When creating a new one}
        {--values=null : PHP value file}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Store a server by a PHP value file or by of arguments';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $payload = $this->payload();
        } catch (FileNotFoundException $exception) {
            $this->error($exception->getMessage());
            return self::INVALID;
        }

        if (!$this->task("Validation", function () use ($payload) {
            $rules = (new ServerStoreRulesFactory())->rules($payload->toArray());
            $validator = Validator::make($payload->toArray(), $rules->toArray());


            if ($validator->fails()) {
                $this->line("");
                foreach ($validator->errors()->toArray() as $property => $errors) {
                    foreach ($errors as $message) {
                        $this->error("{$property}: {$message}");
                    }
                }

                $this->error("Validation failed.");

                return false;
            } else {
                return true;
            }
        })) {
            return self::INVALID;
        }

        if (!$this->task("Register server", function () use ($payload) {
            try {
                $server = $this->forge->createServer($payload->toArray(), true, 1800);
            } catch (TimeoutException $exception) {
                $this->warn("Timeout, the server has not been ready. However, it continues to be created.");
                return true;
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
                return false;
            }

            $this->table([
                "ID", "Name", "Type", "Size", "Region", "Ip Address", "Is Ready", "Sudo Password", "Database Password", "Created At"
            ], collect([[
                $server->id,
                $server->name,
                $server->type,
                $server->size,
                $server->region,
                $server->ipAddress,
                $server->isReady ? 'Yes' : 'No',
                $server->sudoPassword,
                $server->databasePassword,
                $server->createdAt
            ]])->all());

            return true;
        })) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function payload(): Collection
    {
        $payload = collect();
        $options = $this->values();

        if ("22.04" !== $options["ubuntu_version"]) {
            $payload->put("ubuntu_version", $options["ubuntu_version"]);
        }

        if ("app" !== $options["type"]) {
            $payload->put("type", $options["type"]);
        }

        unset($options["ubuntu_version"], $options["type"]);

        foreach ($options as $key => $option) {
            if ("null" !== $option) {
                $payload->put($key, $option);
            }
        }

        return $payload;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
