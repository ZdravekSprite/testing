<template>
  <div class="row" v-bind:class="{ bold: bold, 'hidden': notShow }">
    <div class="col-10 col-s-10">{{ opis }}</div>
    <div class="col-1 col-s-1 center">{{ toKn(sati) }}</div>
    <div class="col-1 col-s-1 right">{{ toKn(iznos) }}</div>
  </div>
</template>

<script>
export default {
  props: ["notShow", "opis", "sati", "iznos", "bold"],
  methods: {
    toKn: function (kn) {
      return isNaN(kn)||kn==='' ? kn : (kn*1).toFixed(2);
    },
  },
};
</script>