<?php

namespace App\Rules;

use Illuminate\Support\Collection;

interface RulesInterface
{
    public function rules(array $data = []): Collection;
}
