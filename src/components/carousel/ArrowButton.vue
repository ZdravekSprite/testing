<template>
<!--  <button
    @click="onClick"
  >
    <img
      class="icon"
      :src="require(`@/assets/svg/${arrowType}.svg`)"
      :alt="arrowType + ' arrow'"
    >
  </button>-->
  <span
    @click="onClick"
    :class="arrowType"
  href='#'></span>
</template>
<script>
export default {
  name: 'ArrowButton',
  props: { arrowType: String, onClick: Function }
}
</script>

<!--<style src="@/assets/styles/ArrowButton.css" scoped/>-->
<style scoped>
/* Next & previous buttons */
.prev:before {
  content: "\2770";/* \e82c &#10094;*/
  margin-left: -3px;
}
.next:before {
  content: "\2771";
  margin-right: -3px;
}
.prev:before, .next:before {
  font-size: 30px;
  color: rgb(255, 255, 255);
  display: block;
  line-height: 40px;
  text-align: center;
}
.prev, .next {
  cursor: pointer;
  background: rgba(0,0,0,0.5);
  position: absolute;
  top: 50%;
  width: 40px;
  height: 40px;
  display: block;
  z-index: 100;
  border-radius: 50%;
  transition: 0.6s ease;
}
/* Position the "next button" to the right */
.prev {
  transform: matrix(.5, 0, 0, .5, 20, -20);
}
/* Position the "next button" to the right */
.next {
  right: 0;
  transform: matrix(.5, 0, 0, .5, -20, -20);
}
/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}
/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next, .text {font-size: 11px}
}
</style>
