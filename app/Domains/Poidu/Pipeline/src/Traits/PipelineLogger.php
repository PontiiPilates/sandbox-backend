<?php

namespace App\Domains\Poidu\Pipeline\src\Traits;

trait PipelineLogger
{
    public function failedClient($model)
    {
        $model->update(['failed' => 'Ошибка при создании клиента MadelineProto. Вероятно следует удалить сессию и авторизоваться вновь. Или выдать права на запись в лог.']);
    }
}
