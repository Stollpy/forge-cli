<?php
namespace App\Factories\Rules;

use App\Enums\Database;
use App\Enums\PHPVersion;
use App\Enums\ServerProvider;
use App\Enums\ServerType;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;

class ServerStoreRulesFactory implements RulesFactoryInterface
{
    public function rules(array $data = []): Collection
    {
        $data = collect($data);
        $rules = collect([
            "provider" => ["required", new Enum(ServerProvider::class)],
            "ubuntu_version" => ["required"],
            "type" => ["required", new Enum(ServerType::class)],
            "php_version" => ["required", new Enum(PHPVersion::class)]
        ]);

        if (!$rules->has("provider") || null === $serverProvider = ServerProvider::tryFrom($data->get("provider"))) {
            return $rules;
        }

        if ($this->publicProviders()->contains($serverProvider)) {
            $rules->put("region", ["required"]);
        }

        if ($serverProvider === ServerProvider::CUSTOM) {
            $rules->put("credential_id", ["required"]);
            $rules->put("ip_address", ["required"]);
            $rules->put("private_ip_address", ["required"]);
        }

        if ($serverProvider === ServerProvider::AWS) {
            if (!$data->has("aws_vpc_id") && !$data->has("aws_vpc_name")) {
                $rules->put("aws_vpc_id", ["required"]);
            }
        }

        if ($serverProvider === ServerProvider::DIGITAL_OCEAN)  {
            if (!$data->has("ocean2_vpc_uuid") && !$data->has("ocean2_vpc_name")) {
                $rules->put("ocean2_vpc_uuid", ["required"]);
            }
        }

        if ($serverProvider === ServerProvider::VULTR)  {
            if (!$data->has("vultr2_network_id") && !$data->has("vultr2_network_name")) {
                $rules->put("vultr2_network_id", ["required"]);
            }
        }

        if ($serverProvider === ServerProvider::HETZNER)  {
            $rules->put("hetzner_network_id", ["required"]);
        }

        if ($data->has("database")) {
            $rules->put("database_type", ["required", new Enum(Database::class)]);
        }

        return $rules;
    }

    private function publicProviders(): Collection
    {
        return collect([
            ServerProvider::AWS,
            ServerProvider::DIGITAL_OCEAN,
            ServerProvider::HETZNER,
            ServerProvider::LINODE,
            ServerProvider::VULTR
        ]);
    }
}
