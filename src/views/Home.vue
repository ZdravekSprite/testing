<template>
  <main class="home">
    <Carousel :cards="cards" height="345px" />
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
