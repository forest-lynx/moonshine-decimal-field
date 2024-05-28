<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Trait;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Support\SelectOptions;
use ReflectionClass;
use UnitEnum;

trait WithUnit
{
    protected ?string $unitColumn = null;
    protected array $unitOptions = [];
    protected mixed $unitDefault = null;

    public function getUnitColumn(): ?string
    {
        return $this->unitColumn;
    }

    public function values(): array
    {
        return $this->unitOptions;
    }

    public function unit(string $column, string|array $data): static
    {
        $this->isGroup = true;
        $this->unitColumn = $column;

        if (is_string($data) && str($data)->isJson()) {
            $values = json_decode(
                $data,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $this->unitOptions = SelectOptions::flatten($values);
        } elseif (is_array($data)) {
            $this->unitOptions = SelectOptions::flatten($data);
        } elseif (class_exists($data) && (new ReflectionClass($data))->isEnum()) {
            $enums = collect($data::cases());
            $this->unitOptions = $enums->mapWithKeys(fn ($enum): array => [
                $enum->value => method_exists($enum, 'toString')
                    ? $enum->toString()
                    : $enum->value,
                ])->toArray();
        }
        return $this;
    }

    public function unitDefault(mixed $default): static
    {
        $this->unitDefault = $default;
        return $this;
    }

    public function isSelected(string $value): bool
    {
        $item = $this?->getData();
        $item = is_array($item)
            ? array_filter($item, fn($i)=>!is_null($i))
            : $item;

        $current = [];

        if (
            (!($item instanceof Model && $item->getKey()) || empty($item))
            && $this->unitDefault
        ) {
            $current = $this->unitDefault;
        } else {
            $current = data_get($item, $this->getUnitColumn());
        }

        return SelectOptions::isSelected($current, $value);
    }

    protected function getUnitPreviewValue(): string
    {
        $value = data_get($this?->getData() ?? [], $this->getUnitColumn());

        if (is_null($value)) {
            return '';
        }

        if ($value instanceof UnitEnum) {
            return method_exists($value, 'toString')
                    ? $value->toString()
                    : $value?->value ?? $value->name;
        }

        if (is_scalar($value)) {
            return data_get(
                $this->values(),
                $value,
                (string) $value
            );
        }
    }
}
