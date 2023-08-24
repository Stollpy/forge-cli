<?php
namespace App\Rules;

use Illuminate\Support\Collection;

class ServerStoreRules implements RulesInterface
{

    public function rules(array $data = []): Collection
    {
        $rules = collect([
            'provider' => ['required'],
            'ubuntu_version' => ['required'],
            'type' => []
        ]);

        return $rules;
    }
}
