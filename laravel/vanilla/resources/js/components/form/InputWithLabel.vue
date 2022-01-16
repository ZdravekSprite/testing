<template>
  <div class="mt-4">
    <label :for=inputID class="block font-medium trxt-sm text-gray-700">{{ labelText }}</label>
    <input
      :id=inputID
      type="number"
      :name=inputID
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
      min="0"
      :step=inputStep
      class="block mt-1 w-32 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      :disabled=disabled
    />
  </div>
</template>

<script>
export default {
  props: ['labelText', 'inputID', 'inputStep', 'disabled', 'value'],
};
</script>
