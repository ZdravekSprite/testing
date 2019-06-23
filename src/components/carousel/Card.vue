<template>
  <div class="card" ref="card"
      :style="{height: cHeight + 'px'}"
  >
<!-- <div class="img-banner" :style="{'background-image': 'url(' + require('./assets/media/baner1.jpg') + ')'}"></div> -->
    <img
      :style="{height: cHeight + 'px'}"
      class="thumbnail"
      :src="require(`@/assets/${imgName}`)"
    />
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    cSize: Object,
    imgName: String
  },
  data () {
    return {
      cHeight: 0
    }
  },
  mounted () {
    this.$nextTick(() => {
      window.addEventListener('resize', this.handleResize)
      this.handleResize()
    })
  },
  methods: {
    handleResize () {
      this.cHeight = this.cSize.height * (this.$refs.card.clientWidth + 77) / (this.cSize.width + 77)
      // console.log(this.$refs.card.clientWidth)
    }
  }
}
</script>

<style scoped>
.card {
  pointer-events: none;
  padding-bottom: 30px !important;
  pointer-events: none;
  z-index: 1;
}
.thumbnail, .card {
  position: relative;
  overflow: hidden;
  width: 100%;
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
  transition: all 10s ease-in-out;
}
</style>
