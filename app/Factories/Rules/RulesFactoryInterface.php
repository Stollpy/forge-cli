<?php

namespace App\Factories\Rules;

use Illuminate\Support\Collection;

interface RulesFactoryInterface
{
    public function rules(array $data = []): Collection;
}
