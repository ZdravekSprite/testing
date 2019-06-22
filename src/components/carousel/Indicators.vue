<template>
  <div
    class="dots"
  >
    <span
      v-for="(_, index) in elements"
      class="dot"
      :key="index"
      :class="{'active': currentElementIndex === index}"
      @click='showElement(index)'
      :disabled=" currentElementIndex === index"
    >
    </span>
  </div>
</template>

<script>
export default {
  name: 'Indicators',
  props: { elements: Array, currentElementIndex: Number, showElement: Function }
}
</script>

<style scoped>
/* Dots */
.dots {
  position: absolute;
  top: 90%;
  width: 100%;
  text-align: center;
}
/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 12px;
  width: 12px;
  margin: 0 2px;
  background-color: #bbb;
  border: 3px solid rgb(229, 229, 229);
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}
.active, .dot:hover {
  background-color: #717171;
  background-color: rgb(102, 102, 102);
}
</style>
