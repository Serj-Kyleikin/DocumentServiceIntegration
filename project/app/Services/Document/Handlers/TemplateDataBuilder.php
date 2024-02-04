<?php

namespace App\Services\Document\Handlers;

use Illuminate\Database\Eloquent\Model;
use Exception;

class TemplateDataBuilder
{
    private Interpreter $query;
    private Model       $model;
    private array       $where;

    public function getValues(array $vars): array
    {
        foreach($vars as $varName => $query) {

            $this->query = $query;

            $this->setModel();
            $this->setWhere();

            $values[$varName] = $this->getModelData();
        }

        return $values;
    }

    /**
     * @throws \Throwable
     */
    private function setModel(): void
    {
        $model = $this->query->model();
        $modelPath = "App\Models\\" . $model;

        throw_if(!class_exists($modelPath),
            Exception::class,
            __('Model: ' . $model . ' missing')
        );

        $this->model = app($modelPath);
    }

    private function setWhere():void
    {
        $this->where = [
            $this->query->whereField() => $this->query->whereValue()
            // добавление данных (auth()->id())
        ];
    }

    private function getModelData(): string|float|int|null
    {
        $field = $this->query->field();

        return $this->model::where($this->where)
            ->select($field)
            ->when($this->query->isWith(), function ($query) {
                return $query->with($this->query->with());
            })
            ->when($this->query->isSum(), function ($query) use ($field) {
                return $query->sum($field);
            }, function ($query) {
                return $query->first();
            })
            ->$field;
    }
}
