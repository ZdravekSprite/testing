<template>
  <div class="card-carousel">
    <Card
      class="current-element"
      :cHeight="this.height"
      :headline="currentElement.headline"
      :text="currentElement.text"
      :imgName="currentElement.imgName"
    />
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
    height: String
  },
  components: { Card, ArrowButton, Indicators },

  data () {
    return {
      currentElementIndex: 0,
      timer: null
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
      this.timer = setInterval(this.showNextElement, 10000)
    },
    stopRotation: function () {
      clearTimeout(this.timer)
      this.timer = null
    },
    showNextElement () {
      this.currentElementIndex = (this.currentElementIndex >= this.cards.length - 1) ? 0 : this.currentElementIndex + 1
    },
    showPrevElement () {
      this.currentElementIndex = (this.currentElementIndex <= 0) ? this.cards.length - 1 : this.currentElementIndex - 1
    },
    showElement (elementIndex) {
      this.currentElementIndex = elementIndex
    }
  }
}
</script>

<style scoped>
.card-carousel {
  position: relative;
  margin: auto;
}
</style>
