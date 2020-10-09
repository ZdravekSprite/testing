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
  bottom: 40px;
  width: 100%;
  text-align: center;
  z-index: 2;
}
/* The dots/bullets/indicators */
.dot.active, .dot:hover {
  background: rgb(102, 102, 102);
}
.dot {
  cursor: pointer;
  height: 12px;
  width: 12px;
  margin: 0 2px;
  /* background-color: #bbb; */
  background: linear-gradient(to bottom, rgb(153, 153, 153) 0%,rgb(225, 225, 225) 100%);
  border: 3px solid rgb(229, 229, 229);
  border-radius: 50%;
  cursor: pointer;
  box-sizing: content-box;
  display: inline-block;
  transition: background-color 0.1s ease;
}
</style>
