<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Fields;

use Closure;
use NumberFormatter;
use MoonShine\Fields\Text;
use ForestLynx\MoonShine\Trait\WithUnit;
use ForestLynx\MoonShine\Trait\WithNumberFormatter;
use MoonShine\Contracts\Fields\DefaultValueTypes\DefaultCanBeArray;

final class Decimal extends Text implements DefaultCanBeArray
{
    use WithNumberFormatter;
    use WithUnit;

    protected string $view = 'moonshine-fl::fields.decimal';
    protected string $locale;
    protected NumberFormatter $formatter;
    protected int $precision = 2;
    protected int $styleFormatter = NumberFormatter::DECIMAL;
    protected bool $isNaturalNumber = false;

    public function __construct(
        Closure|string|null $label = null,
        ?string $column = null,
        ?Closure $formatted = null
    ) {
        parent::__construct($label, $column, $formatted);

        $this->setFormatter();
    }

    public function locale(string $locale): static
    {
        $this->locale = $locale;
        $this->setFormatter();
        return $this;
    }

    protected function getLocale(): string
    {
        return $this->locale ?? app()->getLocale();
    }

    public function resolveFill(array $raw = [], mixed $casted = null, int $index = 0): static
    {
        return parent::resolveFill($raw, $casted, $index);
    }

    public function precision(int $precision, ?bool $isNaturalNumber = false): static
    {
        $this->precision = $precision;

        if (!$this->isNaturalNumber() && $isNaturalNumber) {
            $this->isNaturalNumber = $isNaturalNumber;
        }

        return $this;
    }

    protected function getPrecision(): int
    {
        return $this->precision;
    }

    public function naturalNumber(?int $precision = null): self
    {
        if (
            !is_null($precision)
            && $this->getPrecision() !== $precision
        ) {
            $this->precision($precision);
        }

        $this->isNaturalNumber = true;

        return $this;
    }

    protected function isNaturalNumber(): bool
    {
        return $this->isNaturalNumber;
    }

    protected function resolvePreview(): string
    {
        $resolvePreviewValue = '';
        if ($this->toFormattedValue() === $this->toValue()) {
            $resolvePreviewValue = $this->getDecimalValue() ?? '0';
        } else {
            $resolvePreviewValue = $this->toFormattedValue();
        }

        return $resolvePreviewValue . ($this->isGroup() ? ' ' . $this->getUnitPreviewValue() : '');
    }

    protected function resolveValue(): string
    {
        $value = $this->getDecimalValue() ?? '';

        $this->setAttribute(
            'x-mask:dynamic',
            "\$money(\$input, '{$this->getDecimalSeparator()}', '{$this->getThousandsSeparator()}', '{$this->getPrecision()}')",
        );

        return $value;
    }

    protected function getDecimalValue(): ?string
    {
        //TODO обработка строкового значения не относящегося к установленной локали
        // и не являющейся фактически числом или числом с плавающей точкой.
        if (!isset($this->formatter)) {
            $this->setFormatter();
        }

        $this->checkAndSetFractionDigits();

        if (is_string($this->toValue())) {
            $value = $this->formatter->parse($this->toValue());
            if (!$value) {
                $value = floatval($this->toValue());
            }
        } else {
            $value = $this->toValue() ?? 0;
        }

        if ($this->isNaturalNumber()) {
            $value = $value / pow(10, $this->getPrecision());
        }

        if ($value) {
            $value = $this->formatter->format($value);
            return (string) $value;
        }
        return null;
    }

    protected function resolveOnApply(): ?Closure
    {
        if (!isset($this->formatter)) {
            $this->setFormatter();
        }

        $this->checkAndSetFractionDigits();

        return function ($item) {
            $values = $this->requestValue();

            if (!$values) {
                return $item;
            }

            if ($this->isGroup()) {
                $unit = $values[$this->getUnitColumn()];
                $item->{$this->getUnitColumn()} = $unit;
                $number = $this->formatter->parse((string) ($values[$this->column()] ?? 0));
            } else {
                $number = $this->formatter->parse((string) $values);
            }

            if ($this->isNaturalNumber()) {
                $number = (int) ($number * pow(10, $this->getPrecision()));
            }
            $item->{$this->column()} = $number;

            return $item;
        };
    }
}
