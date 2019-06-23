<template>
  <main class="home">
      <Carousel
      :cards="cards"
      :cSize="cSize"
    />
  </main>
</template>

<script>
import Carousel from '@/components/carousel/Carousel'
export default {
  title () {
    return `Andi-isoliertechnik toplinska,zvučna i protupožarna izolacija`
  },
  name: 'home',
  components: {
    Carousel
  },
  data () {
    return {
      cSize: {
        width: 1100,
        height: 360
      },
      cards: [
        {
          headline: '',
          text: '',
          imgName: 'iso1.jpg'
        },
        {
          headline: '',
          text: '',
          imgName: 'iso2.jpg'
        },
        {
          headline: '',
          text: '',
          imgName: 'iso3.jpg'
        }
      ]
    }
  }
}
</script>

<style>
.example-1 {
  width: 100px;
}

.example-2 {
  width: 200px;
}

.example-3 {
  width: 400px;
}
</style>
