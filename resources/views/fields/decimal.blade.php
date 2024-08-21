<div class="decimal-field {{$element->isGroup() ? 'decimal-field-group': ''}}">

    <x-moonshine::form.input-extensions
        :extensions="$element->getExtensions()"
    >
        <x-moonshine::form.input
            :attributes="$element->attributes()->merge([
                'id' => $element->id(),
                'name' => $element->name($element->column()),
                'value' => (string) (is_array($value) ? $value[$element->column()] : $value)
            ])"
            @class(['form-invalid' => formErrors($errors, $element->getFormName())->has($element->column())])
        />
    </x-moonshine::form.input-extensions>
    @if($element->isGroup())
        <x-moonshine::form.select
            :id="$element->getUnitColumn()"
            :name="$element->name($element->getUnitColumn())"
            data-item-select-text=""

            @class(['form-invalid' => formErrors($errors, $element->getFormName())->has($element->getUnitColumn())])
        >
            <x-slot:options>
                @foreach($element->values() as $key => $unit)
                    <option value="{{ $key }}"
                    @if($element->isSelected($key)) selected @endif
                            >{{ $unit }}</option>
                @endforeach
            </x-slot:options>
        </x-moonshine::form.select>
    @endif
</div>
@if($element->isGroup())
    @error($element->column(), $element->getFormName())
        <x-moonshine::form.input-error>
            {{ $message }}
        </x-moonshine::form.input-error>
    @enderror
    @error($element->getUnitColumn(), $element->getFormName())
        <x-moonshine::form.input-error>
            {{ $message }}
        </x-moonshine::form.input-error>
    @enderror
@endif
