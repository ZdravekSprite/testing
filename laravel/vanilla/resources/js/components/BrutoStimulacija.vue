<template>
  <div class="brutoStimulacijaComponent">
    <div class="heading">
      <h2 id="title">I'm an bruto stimulacija component.</h2>
    </div>
    <div class="mt-4">
      <label for="iznos" class="block font-medium trxt-sm text-gray-700"
        >Iznos 2.8. Stimulacija bruto</label
      >
      <input
        id="iznos" type="number" name="iznos" v-model="bs.iznos" min="0" step="0.01"
        class="block mt-1 w-32 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      />
    </div>
    <div class="mt-4">
      <label for="riznos" class="block font-medium trxt-sm text-gray-700"
        >Iznos 1.1. Za redovni rad</label
      >
      <input
        id="riznos" type="number" name="riznos" v-model="redovni.iznos" min="0" step="0.01"
        class="block mt-1 w-32 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      />
    </div>
    <div class="mt-4">
      <label for="rsati" class="block font-medium trxt-sm text-gray-700"
        >Sati 1.1. Za redovni rad</label
      >
      <input
        id="rsati" type="number" name="rsati" v-model="redovni.sati" min="0" step="1"
        class="block mt-1 w-32 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      />
    </div>
    <div class="mt-4">
      <label for="sati" class="block font-medium trxt-sm text-gray-700"
        >Sati 2.8. Stimulacija bruto</label
      >
      <input
        id="sati" type="time" name="sati" v-model="bs.sati"
        class="block mt-1 w-32 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
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
    }
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
