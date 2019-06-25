<template>
  <div class="gmap" :id="mapName"></div>
</template>

<script>
import gmapsInit from '@/utils/gmaps'

export default {
  name: 'g-map',
  props: ['name', 'lat', 'lng'],
  data: function () {
    return {
      mapName: this.name + '-map'
    }
  },
  async mounted () {
    try {
      const google = await gmapsInit()
      let latlng = new google.maps.LatLng(this.lat, this.lng)
      var mapOptions = {
        zoom: 9,
        center: latlng
      }
      let map = new google.maps.Map(this.$el, mapOptions)
      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        title: 'Bednjica 61A, 42250 Lepoglava'
      })
      marker.setMap(map)
    } catch (error) {
      console.error(error)
    }
  }
}
</script>

<style>
.gmap {
  height: 255px;
}
</style>
