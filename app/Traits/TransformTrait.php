<?php

namespace App\Traits;

trait TransformTrait
{

    public function arrayToObject(array $array)
    {
        $data = json_encode($array);
        $data = json_decode($data);

        return $data;
    }
}
