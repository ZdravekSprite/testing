<template>
  <div class="gmap" :id="mapName"></div>
</template>

<script>
// import gmapsInit from '@/utils/gmaps'

export default {
  name: 'g-map',
  props: ['name', 'lat', 'lng'],
  data: function () {
    return {
      mapName: this.name + '-map',
      markerCoordinates: [{
        latitude: this.lat,
        longitude: this.lng
      }],
      map: null,
      bounds: null,
      markers: []
    }
  },
  async mounted () {
    try {
      // const google = await gmapsInit()
      // const geocoder = new google.maps.Geocoder()
      // const latlng = new google.maps.LatLng(this.lat, this.lng)
      // var mapOptions = {
      //   zoom: 9,
      //   center: latlng
      // }
      // const map = new google.maps.Map(this.$el, mapOptions)
      // var marker = new google.maps.Marker({
      //   map: map,
      //   position: latlng
      // })
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
