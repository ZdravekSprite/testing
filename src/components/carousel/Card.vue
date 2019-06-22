<template>
  <div class="card"
    :style="{height: this.cHeight}"
  >
    <img
      class="thumbnail"
      :style="{height: this.cHeight}"
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
    cHeight: String,
    headline: String,
    imgName: String,
    text: String
  }
}
</script>

<style scoped>
.card {
  background-color: white;
  border-radius: 5px;
  box-shadow: 0 20px 60px 0 rgba(41, 67, 100, 0.21);
}
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
  width: 100%;
  height: 550px;
  overflow: hidden;
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
.thumbnail img.portrait {
  width: 100%;
  height: auto;
}</style>
