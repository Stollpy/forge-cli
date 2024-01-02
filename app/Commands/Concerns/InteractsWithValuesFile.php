<?php

namespace App\Commands\Concerns;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

trait InteractsWithValuesFile
{
    /**
     * make options from a php value file in a Command.
     * @return array
     */
    protected function values(): array
    {
        $options = $this->options();

        if (!isset($options["values"]) || "null" === $values = $options["values"]) {
            return $options;
        }

        if (!file_exists($values)) {
            throw new FileNotFoundException($values);
        }

        foreach (require $values as $option => $value) {
            $options[$option] = $value;
        }

        unset($options["values"]);

        return $options;
    }
}
