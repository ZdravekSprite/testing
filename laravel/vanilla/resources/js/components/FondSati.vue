<template>
  <div>
    <h2>Koliki je fond sati.</h2>
    <div class="grid grid-cols-1 md:grid-cols-2">
      <SelectWithLabel labelText="mjesec" selectID="mjeseci" v-model="mjeseci" />
      <SelectWithLabel labelText="godina" selectID="godine" v-model="godine" />
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      mjesec: {
        mjesec: 1,
        godina: 2022,
      },
      mjeseci: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      godine: [2020, 2021, 2022, 2023],
    };
  },
};
</script>
