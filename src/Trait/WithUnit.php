<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Trait;

use Closure;
use ReflectionClass;
use MoonShine\UI\Fields\Enum;
use MoonShine\UI\Fields\Select;
use MoonShine\Support\DTOs\Select\Options;

trait WithUnit
{
    protected null|Select|Enum $unitField = null;

    public function unit(?string $column, Closure|array|Options|string $data, ?Closure $formatted = null): static
    {
        if (
            is_string($data)
            && class_exists($data)
            && (new ReflectionClass($data))->isEnum()
        ) {
            $this->unitField = Enum::make(column: $column, formatted: $formatted)->attach($data);
        } elseif (!is_string($data)) {
            $this->unitField = Select::make(column: $column, formatted: $formatted)
                ->withoutWrapper()->options($data);
        }

        $this->unitField?->formName($this->getFormName());

        return $this;
    }

    public function unitDefaultValue(mixed $default): static
    {
        $this->unitField?->default($default);

        return $this;
    }

    public function unitNullable(): static
    {
        $this->unitField?->nullable();

        return $this;
    }

    public function unitSearchable(): static
    {
        $this->unitField?->searchable();

        return $this;
    }

    protected function getUnitField(): null|Select|Enum
    {
        return (clone $this->unitField)?->fillData($this->getData());
    }

    protected function isUnitField(): bool
    {
        return ! \is_null($this->unitField);
    }
}
