<template>
  <div class="card" ref="card"
      :style="{height: cHeight + 'px'}"
  >
    <img
      :style="{height: cHeight + 'px'}"
      class="thumbnail"
      :src="require(`@/assets/${imgName}`)"
      :alt="headline"
    />
    <div class='card-content'>
      <h3 class='headline'>{{headline}}</h3>
      <p>{{text}}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    cSize: Object,
    imgName: String,
    headline: String,
    text: String
  },
  data () {
    return {
      cHeight: 0
    }
  },
  mounted: function () {
    this.handleResize()
  },
  created () {
    window.addEventListener('resize', this.handleResize)
    this.handleResize()
  },
  destroyed () {
    window.removeEventListener('resize', this.handleResize)
  },
  methods: {
    handleResize () {
      this.cHeight = this.cSize.height * this.$refs.card.clientWidth / this.cSize.width
    }
  }
}
</script>

<style scoped>
.headline {
  font-weight: bold;
}
.card-icon {
  pointer-events: none;
}
.card-content {
  padding: 0px;
}
.thumbnail, .card {
  position: relative;
  overflow: hidden;
  text-align: center;
}
.thumbnail img {
  position: absolute;
  left: 50%;
  top: 50%;
  height: 100%;
  width: auto;
  -webkit-transform: translate(-50%,-50%);
      -ms-transform: translate(-50%,-50%);
          transform: translate(-50%,-50%);
}
</style>
