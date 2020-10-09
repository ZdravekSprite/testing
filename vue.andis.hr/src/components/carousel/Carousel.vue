<template>
  <div class="card-carousel">
    <Card
      class="current-element"
      :cSize="cSize"
      :headline="currentElement.headline"
      :text="currentElement.text"
      :imgName="currentElement.imgName"
    />
    <div class="timer"
      :style="{width: (this.bar / 10) + '%', visibility: 'visible'}"
    ></div>
    <!-- <div>{{this.bar}}</div> -->
    <ArrowButton
      arrowType="prev"
      :onClick="showPrevElement"
    />
    <ArrowButton
      arrowType="next"
      :onClick="showNextElement"
    />
    <Indicators
      :elements="this.cards"
      :currentElementIndex="this.currentElementIndex"
      :showElement="this.showElement"
    />
  </div>
</template>
<script>
import Card from './Card'
import ArrowButton from './ArrowButton'
import Indicators from './Indicators'

export default {
  name: 'Carousel',
  props: {
    cards: Array,
    cSize: Object
  },
  components: { Card, ArrowButton, Indicators },

  data () {
    return {
      currentElementIndex: 0,
      timer: null,
      bar: 0
    }
  },
  mounted: function () {
    this.startRotation()
  },
  computed: {
    currentElement () {
      return this.cards[this.currentElementIndex]
    }
  },
  methods: {
    startRotation: function () {
      this.timer = setInterval(this.progressBar, 10)
    },
    stopRotation: function () {
      clearTimeout(this.timer)
      this.timer = null
    },
    showNextElement () {
      this.bar = 0
      this.currentElementIndex = (this.currentElementIndex >= this.cards.length - 1) ? 0 : this.currentElementIndex + 1
    },
    showPrevElement () {
      this.bar = 0
      this.currentElementIndex = (this.currentElementIndex <= 0) ? this.cards.length - 1 : this.currentElementIndex - 1
    },
    showElement (elementIndex) {
      this.bar = 0
      this.currentElementIndex = elementIndex
    },
    progressBar () {
      if (this.bar >= 1000) {
        this.showNextElement()
        this.bar = 0
      } else {
        this.bar++
      }
    }
  }
}
</script>

<style scoped>
.card-carousel {
  position: relative;
  width: 100% !important;
  margin: 0 !important;
  padding: 0 !important;
  padding-bottom: 30px !important;
  overflow:visible !important;
}
.card-carousel:before, .card-carousel:after
{
  /* z-index: -2; */
  position: absolute;
  content: "";
  bottom: 40px;
  left: 10px;
  width: 50%;
  top: 85%;
  max-width:300px;
  background: transparent;
  box-shadow: 0 15px 10px rgba(0,0,0,0.8);

  transform: rotate(-3deg);
}

.card-carousel:after
{
  transform: rotate(3deg);
  right: 10px;
  left: auto;
}
.timer {
  visibility: hidden;
  width: 100%;
  height: 5px;
  background: rgb(0,0,0,1);
  position: absolute;
  z-index: 200;
  transform: translate3d(0px, 0px, 0px);
  top: 0px;
}
</style>
