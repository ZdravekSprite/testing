<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Platna lista
        <select :value="year" v-model="year">
          <option
            v-for="option in optionsYears"
            :value="option.value"
            :key="option.value"
          >
            {{ option.label }}
          </option>
        </select>
        <select :value="month" v-model="month">
          <option
            v-for="option in optionsMonths"
            :value="option.value"
            :key="option.value"
          >
            {{ option.label }}
          </option>
        </select>
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <platna-lista :year="year" :month="month" :bruto="bruto" />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import PlatnaLista from "@/Sprite/PlatnaLista";

export default {
  components: {
    AppLayout,
    PlatnaLista,
  },
  data: () => {
    return {
      optionsYears: [
        { value: 2020, label: 2020 },
        { value: 2021, label: 2021 },
      ],
      year: new Date().getFullYear(),
      optionsMonths: [
        { value: 1, label: "siječanj" },
        { value: 2, label: "veljača" },
        { value: 3, label: "ožujak" },
        { value: 4, label: "travanj" },
        { value: 5, label: "svibanj" },
        { value: 6, label: "lipanj" },
        { value: 7, label: "srpanj" },
        { value: 8, label: "kolovoz" },
        { value: 9, label: "rujan" },
        { value: 10, label: "listopad" },
        { value: 11, label: "studeni" },
        { value: 12, label: "prosinac" },
      ],
      month: new Date().getMonth() + 1,
      bruto: 530000,
    };
  },
};
</script>
