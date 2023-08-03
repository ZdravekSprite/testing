@props(['value'])

<p {!! $attributes->merge(['class' => 'font-medium mt-1 text-sm text-gray-600 dark:text-gray-400']) !!}>
  {{ $value ?? $slot }}
</p>
