<template>
  <div>
    <div v-for="(day, index) in days" :key="index">
      <list-day :day="day" class="day" v-on:daychanged="$emit('reloadlist')" />
    </div>
  </div>
</template>

<script>
import listDay from "./listDay";
export default {
  props: ["days"],
  components: {
    listDay,
  },
};
</script>

<style scoped>
.day {
  background: #e6e6e6;
  padding: 5px;
  margin-top: 5px;
}
</style>