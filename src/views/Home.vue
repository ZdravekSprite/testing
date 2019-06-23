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
        width: 1110,
        height: 360
      },
      cards: [
        {
          imgName: 'iso1.jpg'
        },
        {
          imgName: 'iso2.jpg'
        },
        {
          imgName: 'iso3.jpg'
        }
      ]
    }
  }
}
</script>
