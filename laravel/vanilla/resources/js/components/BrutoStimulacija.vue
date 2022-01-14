<template>
  <div>
    <h2>Koliko prekovremenih sati je u Bruto stimulaciji.</h2>
    <div class="grid grid-cols-1 md:grid-cols-3">
      <div class="mt-4">
        <label for="iznos" class="block font-medium trxt-sm text-gray-700"
          >Iznos 2.8. Stimulacija bruto</label
        >
        <input
          id="iznos"
          type="number"
          name="iznos"
          v-model="bs.iznos"
          min="0"
          step="0.01"
          @change="calcSati()"
          class="
            block
            mt-1
            w-32
            rounded-md
            shadow-sm
            border-gray-300
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
          "
        />
      </div>
      <div class="mt-4">
        <label for="riznos" class="block font-medium trxt-sm text-gray-700"
          >Iznos 1.1. Za redovni rad</label
        >
        <input
          id="riznos"
          type="number"
          name="riznos"
          v-model="redovni.iznos"
          min="0"
          step="0.01"
          @change="calcSati()"
          class="
            block
            mt-1
            w-32
            rounded-md
            shadow-sm
            border-gray-300
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
          "
        />
      </div>
      <div class="mt-4">
        <label for="rsati" class="block font-medium trxt-sm text-gray-700"
          >Sati 1.1. Za redovni rad</label
        >
        <input
          id="rsati"
          type="number"
          name="rsati"
          v-model="redovni.sati"
          min="0"
          step="1"
          @change="calcSati()"
          class="
            block
            mt-1
            w-32
            rounded-md
            shadow-sm
            border-gray-300
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
          "
        />
      </div>
    </div>
    <div class="mt-4">
      <label for="sati" class="block font-medium trxt-sm text-gray-700"
        >Sati 2.8. Stimulacija bruto</label
      >
      <input
        id="sati"
        type="number"
        name="sati"
        v-model="bs.sati"
        class="
          block
          mt-1
          w-32
          rounded-md
          shadow-sm
          border-gray-300
          focus:border-indigo-300
          focus:ring
          focus:ring-indigo-200
          focus:ring-opacity-50
        "
        disabled="disabled"
      />
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      bs: {
        sati: 0,
        iznos: 0,
      },
      redovni: {
        sati: 0,
        iznos: 0,
      },
    };
  },
  methods: {
    calcSati() {
      if (this.redovni.sati == 0) {
        return;
      }
      if (this.redovni.iznos == 0) {
        return;
      }
      if (this.bs.iznos == 0) {
        return;
      }
      this.bs.sati =
        Math.round(
          (this.bs.iznos / 1.5 / this.redovni.iznos) * this.redovni.sati * 100
        ) / 100;
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
