<template>
  <main class="home">
    <Carousel  id="slider"
      :cards="cards"
      :cSize="cSize"
    />
    <aside id="info">
      <h3>ANDY-ISOLIERTECHNIK d.o.o.</h3>
      <p>Koristeći revolucionarne tehnike izvodimo izolatorske radove na području toplinske, zvučne i protupožarne izolacije te proizvodimo nove i patentirane materijale koji garantiraju sigurnost i kvalitetu.<br>
      Ukoliko ste zainteresirani za naše proizvode kontaktirajte nas putem maila ili kontakt obrasca!<br>
      Email: andis@andis.hr, mob: +385992075545, Bednjica 61A, 42250 Lepoglava<br>
      Direktor tvrtke: Jagarinec Andro</p>
    </aside>
    <aside id="map">
      <h3>Kako do nas?</h3>
      <GoogleMap
        name="andis.hr"
        lat=46.2895307
        lng=15.9822823
      />
    </aside>
    <aside id="form">
      <h3>Kontakt</h3>
      <ContactForm />
    </aside>
  </main>
</template>

<script>
import Carousel from '@/components/carousel/Carousel'
import GoogleMap from '@/components/GoogleMap'
import ContactForm from '@/components/ContactForm'
export default {
  title () {
    return `Andi-isoliertechnik toplinska,zvučna i protupožarna izolacija`
  },
  name: 'home',
  components: {
    Carousel,
    GoogleMap,
    ContactForm
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

<style scoped>
main {
  display:grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 30px;
  grid-template-areas:
    "slider slider slider"
    "info map form";
}
aside {
  padding: 0;
}
#slider {
  grid-area: slider;
}
#info {
  grid-area: info;
}
#map {
  grid-area: map;
}
#form {
  grid-area: form;
}
@media (max-width: 992px) {
  main {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto auto 1fr;
    grid-template-areas:
      "slider slider"
      "info form"
      "map form";
  }
}
@media (max-width: 768px) {
  main {
    grid-template-columns: auto;
    grid-template-rows: auto;
    grid-template-areas:
      "slider"
      "info"
      "map"
      "form";
  }
}
h3, .h3 {
  margin-top: 0;
  font-size: 24px;
  color: #676767;
  margin-bottom: 10px;
  font-weight: 500;
  line-height: 1.1;
  clear: both;
}
main p {
  line-height: 1.7em;
  font-size: 14px;
}
</style>
