@props(['value'])

<p {!! $attributes->merge(['class' => 'text-sm font-medium text-gray-600']) !!}>
  {{ $value ?? $slot }}
</p>
