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
  head: {
    // title() {return `Andi-isoliertechnik toplinska,zvučna i protupožarna izolacija`} ,
    meta: [
      { name: "description", content: "Andy-isoliertechnik izvodi izolaterske radove te proizvodi toplinsku, protupožarnu i zvučnu izolaciju u građevinarstvu revolucionarnim novim metodama" },
      { name: "keywords", content: "toplinska, zvučna i protupožarna izolacija" },
      { name: "twitter:card", content: "summary" },
      { property: "twitter:title", content: "Andi-isoliertechnik toplinska,zvučna i protupožarna izolacija" },
      { name: "twitter:description", content: "Andy-isoliertechnik izvodi izolaterske radove te proizvodi toplinsku, protupožarnu i zvučnu izolaciju u građevinarstvu revolucionarnim novim metodama" },
      // { name: "twitter:site", content: "@jqEmprendedorVE" },
      // { name: "twitter:creator", content: "@jqEmprendedorVE" },
      // { name: "twitter:url", content: "https://vue-firebase-ssr.firebaseapp.com" },
      // { name: "twitter:image", content: "https://www.filepicker.io/api/file/nS7a8itSTcaAsyct6rVp" },
      { property: "og:locale", content: "hr_HR" },
      { property: "og:title", content: "summary" },
      { property: "og:type", content: "website" },
      // { property: "og:url", content: "summary" },
      // { property: "og:image", content: "summary" }
      // { property: "og:image:width", content: "512" }
      // { property: "og:image:height", content: "512" }
    ]
  },
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
</style>
