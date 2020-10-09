<template>
  <main class="gallery">
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
    return `Andi-isoliertechnik galerija`
  },
  name: 'gallery',
  components: { Carousel },
  data () {
    return {
      cSize: {
        width: 1110,
        height: 550
      },
      cards: [
        {
          imgName: 'iso_gal_01.jpg'
        },
        {
          imgName: 'iso_gal_02.jpg'
        },
        {
          imgName: 'iso_gal_03.jpg'
        },
        {
          imgName: 'iso_gal_04.jpg'
        },
        {
          imgName: 'iso_gal_05.jpg'
        },
        {
          imgName: 'iso_gal_06.jpg'
        },
        {
          imgName: 'iso_gal_07.jpg'
        },
        {
          imgName: 'iso_gal_08.jpg'
        },
        {
          imgName: 'iso_gal_09.jpg'
        },
        {
          imgName: 'iso_gal_10.jpg'
        },
        {
          imgName: 'iso_gal_11.jpg'
        },
        {
          imgName: 'iso_gal_12.jpg'
        },
        {
          imgName: 'iso_gal_13.jpg'
        },
        {
          imgName: 'iso_gal_14.jpg'
        }
      ]
    }
  }
}
</script>
