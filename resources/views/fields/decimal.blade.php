@props([
    'value' => '',
    'extensions' => [],
    'extensionsAttributes' => null,
    'unitField' => null,
])

<div class="decimal-field {{$unitField ? 'decimal-field-group': ''}}">
    <x-moonshine::form.input-extensions
    :extensions="$extensions"
    :attributes="$extensionsAttributes"
    >
        <x-moonshine::form.input
            :attributes="$attributes->merge([
                'value' => $value
            ])"
        />
    </x-moonshine::form.input-extensions>
    @if($unitField)
        {!! $unitField !!}
    @endif
</div>
