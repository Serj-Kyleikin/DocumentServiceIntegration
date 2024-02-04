<?php

namespace App\Services\Document\Handlers;

class Interpreter
{
    private array  $model;
    private string $whereField;
    private string $whereValue;
    private bool   $isWith = false;

    public function __construct(string $matche)
    {
        $this->setModel($matche);
        $this->setRelations();
    }

    private function setModel(string $matche): void
    {
        $data = explode(';', $matche);
        $where = explode('=', $data[1]);

        $this->model = explode('=', $data[0]);
        $this->whereField = $where[0];
        $this->whereValue = $where[1];
    }

    private function setRelations(): void
    {
        if(preg_match('#:#', $this->whereField)) {

            $this->whereField = substr($this->whereField, 1);
            $this->isWith = true;
        }
    }

    public function model(): string
    {
        return ucfirst(substr($this->model[0], 2));
    }

    public function field(): string
    {
        return $this->model[1];
    }

    public function whereField(): string
    {
        return $this->whereField . '_id';
    }

    public function whereValue(): string
    {
        return substr($this->whereValue, 0, -1);
    }

    public function isWith(): bool
    {
        return $this->isWith;
    }

    public function with(): string|null
    {
        return $this->isWith ? $this->whereField : null;
    }

    public function isSum(): bool
    {
        return false;
    }
}
