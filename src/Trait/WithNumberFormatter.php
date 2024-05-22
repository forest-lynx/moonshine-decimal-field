<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Trait;

use NumberFormatter;

trait WithNumberFormatter
{
    protected function setFormatter(): void
    {
        $this->formatter = new NumberFormatter($this->getLocale(), $this->styleFormatter);
        $this->getFractionDigits();
    }

    protected function setFractionDigits(): void
    {
        $this->formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $this->getPrecision());
    }

    protected function getFractionDigits(): int
    {
        return $this->formatter->getAttribute(NumberFormatter::FRACTION_DIGITS);
    }

    protected function getThousandsSeparator(): string
    {
        return $this->formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
    }

    protected function getDecimalSeparator(): string
    {
        return $this->formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
    }

    protected function checkAndSetFractionDigits(): void
    {
        if ($this->getPrecision() !== $this->getFractionDigits()) {
            $this->setFractionDigits();
        }
    }
}
