<?php

declare(strict_types=1);

namespace ForestLynx\MoonShine\Fields;

use Closure;
use MoonShine\UI\Fields\Enum;
use NumberFormatter;
use ForestLynx\MoonShine\Trait\WithUnit;
use ForestLynx\MoonShine\Trait\WithNumberFormatter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use MoonShine\AssetManager\Css;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\UI\Fields\FormElement;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Sets\UpdateOnPreviewPopover;

final class Decimal extends Text
{
    use WithNumberFormatter;
    use WithUnit;

    protected string $view = 'moonshine-fl::fields.decimal';
    protected ?string $locale = null;
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

    protected function resolveValue(): string
    {
        $value = $this->getDecimalValue() ?? '';

        $this->setAttribute(
            'x-mask:dynamic',
            "\$money(\$input, '{$this->getDecimalSeparator()}', '{$this->getThousandsSeparator()}', '{$this->getPrecision()}')",
        );

        return $value;
    }

    protected function resolveRender(): Renderable|Closure|string
    {
        if ($this->isUnitField()) {
            if (is_null($this->getFormattedValueCallback())) {
                $this->setFormattedValueCallback(
                    fn ($m, $i, self $f): string => \sprintf(
                        "%s %s",
                        $f->getDecimalValue() ?? '0',
                        $f->getUnitField()?->preview() ?? ''
                    )
                );
            }

            if (
                $this->isUpdateOnPreview() && $this->isPreviewMode()
                && !($this->updateOnPreviewPopover && $this->updateOnPreviewParentComponent && $this->isPreviewMode())
            ) {
                $this->updateInPopover(
                    (string) app(MoonShineRequest::class)?->getResource()?->getListComponentName()
                );
            }
        }
        return parent::resolveRender();
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
            $value = $this->getRequestValue();
            if ($this->isUnitField()) {
                /** @var FieldContract $unitField */
                $unitField = $this->getUnitField();
                $item->{$unitField->getColumn()} = $unitField->getRequestValue();
            }
            if (!$value) {
                return $item;
            }

            $number = $this->formatter->parse((string) $value);

            if (!$number) {
                return $item;
            }

            if ($this->isNaturalNumber()) {
                $number = (int) ($number * pow(10, $this->getPrecision()));
            }

            $item->{$this->getColumn()} = $number;

            return $item;
        };
    }

    protected function viewData(): array
    {
        return [
        ...parent::viewData(),
        'unitField' => $this->getUnitField(),
        ];
    }

    public function getAssets(): array
    {
        return [
            Css::make('vendor/moonshine-decimal-field/css/decimal-field.css')
        ];
    }
}
