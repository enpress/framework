<?php

namespace Enpress\Database;

trait ACFieldsTrait
{

    /**
     * Get several fields in one request
     *
     * @param $names
     * @return array
     */
    public function fields($names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $return = [];

        foreach ($names as $name) {
            $return[$name] = $this->field($name);
        }

        return $return;
    }

}