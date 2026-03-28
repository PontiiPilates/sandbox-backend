<?php

namespace App\Domains\Poidu\Pipeline\Traits;

trait PipelineLogger
{
    public function failed($model, $message)
    {
        $model->update(['failed' => $message]);
    }
}
