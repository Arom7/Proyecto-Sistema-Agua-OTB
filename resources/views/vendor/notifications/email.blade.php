<x-mail::message>

{{-- Logo --}}
<div style="text-align: center;">
    <img src="{{ asset('images/logo.png') }}" alt="Logo de la Empresa" style="max-width: 200px;">
    <div style="text-align: center;">
        <h1>Sistema AquaCube </h1>
    </div>
</div>

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Ups, parece que sucedio algo!')
@else
# @lang('Hola , este es un correo de parte de Aqua-Cube!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Saludos'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
    @lang(
        "Si tienes problemas para hacer clic en el botón \":actionText\", copia y pega la URL a continuación\n".
        'en tu navegador web:',
        [
            'actionText' => $actionText,
        ]
    ) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
