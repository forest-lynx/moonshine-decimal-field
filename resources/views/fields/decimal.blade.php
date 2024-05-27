<x-moonshine::grid class="decimal-field">
    <x-moonshine::column adaptiveColSpan="6" colSpan="{{ $element->isGroup() ? '8' : '12' }}">
        <x-moonshine::form.input-extensions
                    :extensions="$element->getExtensions()"
            >
            <x-moonshine::form.input
                :attributes="$element->attributes()->merge([
                    'id' => $element->id(),
                    'name' => $element->name($element->column()),
                    'value' => (string) $value
                ])"
                @class(['form-invalid' => formErrors($errors, $element->getFormName())->has($element->name($element->column()))])
            />
        </x-moonshine::form.input-extensions>
    </x-moonshine::column>
    @if($element->isGroup())
    <x-moonshine::column adaptiveColSpan="6" colSpan="4">
            <x-moonshine::form.select
                :id="$element->getUnitColumn()"
                :name="$element->name($element->getUnitColumn())"
                data-item-select-text=""
            >
                <x-slot:options>
                    @foreach($element->values() as $key => $unit)
                        <option value="{{ $key }}"
                        @if($element->isSelected($key)) selected @endif
                                >{{ $unit }}</option>
                    @endforeach
                </x-slot:options>
            </x-moonshine::form.select>
    </x-moonshine::column>
    @endif
</x-moonshine::grid>
