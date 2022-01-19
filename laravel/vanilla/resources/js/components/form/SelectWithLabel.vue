<template>
  <div class="mt-4">
    <label :for="selectID" class="block font-medium trxt-sm text-gray-700">{{ labelText }}</label>
    <select
      :id="selectID"
      :name="selectID"
      v-model="model"
      class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    >
      <option
        v-for="option in options"
        :key="option"
        :value="option"
        v-on:input="$emit('input', $event.target.value)"
      >{{ option }}</option>
    </select>
  </div>
</template>

<script>
export default {
  props: ['labelText', 'selectID', 'value', 'options'],
  computed: {
    model: {
      get() { return this.value },
      set(value) { this.$emit('input', value) }
    }
  },
};
</script>
