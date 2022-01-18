<template>
  <div>
    <h2>Koliko prekovremenih sati je u Bruto stimulaciji.</h2>
    <div class="grid grid-cols-1 md:grid-cols-3">
      <InputWithLabel
        labelText="Iznos 2.8. Stimulacija bruto"
        inputID="brutoIznos"
        v-model="bs.iznos"
        inputStep="0.01"
        v-on:input="calcSati()"
      />
      <InputWithLabel
        labelText="Iznos 1.1. Za redovni rad"
        inputID="redovniIznos"
        v-model="redovni.iznos"
        inputStep="0.01"
        v-on:input="calcSati()"
      />
      <InputWithLabel
        labelText="Sati 1.1. Za redovni rad"
        inputID="redovniSati"
        v-model="redovni.sati"
        v-on:input="calcSati()"
        inputStep="1"
      />
    </div>
    <InputWithLabel
      labelText="Sati 2.8. Stimulacija bruto"
      inputID="brutoSati"
      v-model="bs.sati"
      disabled
    />
  </div>
</template>

<script>
import InputWithLabel from "./form/InputWithLabel"

export default {
  components: {
    InputWithLabel,
  },
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
};
</script>
