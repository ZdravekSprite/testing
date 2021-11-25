@props(['value' , 'hiden'])

<div {!! $attributes->merge(['class' => 'mt-4 mx-1']) !!}>
  {{ $value ?? $slot }}
</div>
