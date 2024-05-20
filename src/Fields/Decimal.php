<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Fields;

use Closure;
use NumberFormatter;
use MoonShine\Fields\Text;

final class Decimal extends Text
{
    protected string $locale;
    protected NumberFormatter $formatter;
    protected int $precision = 2;
    protected int $styleFormatter = NumberFormatter::DECIMAL;

    protected function afterMake(): void
    {
        $this->locale = app()->getLocale();
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
        return $this->locale;
    }

    public function precision(int $value): void
    {
        $this->precision = $value;
    }

    protected function getPrecision(): int
    {
        return $this->precision;
    }

    protected function setFormatter(): void
    {
        $this->formatter = new NumberFormatter($this->getLocale(), $this->styleFormatter);
    }

    protected function getThousandsSeparator(): string
    {
        return $this->formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
    }

    protected function getDecimalSeparator(): string
    {
        return $this->formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
    }

    protected function resolvePreview(): string
    {
        if ($this->toFormattedValue() === $this->toValue()) {
            return $this->getDecimalValue();
        }
        return $this->toFormattedValue();
    }

    protected function resolveValue(): string
    {
        $value = $this->getDecimalValue();

        $this->setAttribute(
            'x-mask:dynamic',
            "\$money(\$input, '{$this->getDecimalSeparator()}', '{$this->getThousandsSeparator()}', '{$this->getPrecision()}')",
        );

        return $value;
    }

    public function getDecimalValue(): string
    {
        if (!isset($this->formatter)) {
            $this->setFormatter();
        }

        $value = is_string($this->toValue())
            ? $this->formatter->parse($this->toValue())
            : $this->toValue();

        $value = $this->formatter->format($value);

        return (string) $value;
    }

    protected function resolveOnApply(): ?Closure
    {
        if (!isset($this->formatter)) {
            $this->setFormatter();
        }

        return function ($item) {
            $item->{$this->column()} = $this->formatter->parse((string) $this->requestValue());

            return $item;
        };
    }
}
