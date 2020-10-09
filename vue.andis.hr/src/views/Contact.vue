<template>
  <main class="contact">
    <aside id="info">
      <h3>ANDY-ISOLIERTECHNIK d.o.o.</h3>
      <p>Direktor tvrtke: Jagarinec Andro<br>
      Email: andis@andis.hr<br>
      Mob: +385992075545<br>
      Bednjica 61A, 42250 Lepoglava</p>
    </aside>
    <aside id="map">
      <GoogleMap
        name="andis.hr"
        lat=46.2895307
        lng=15.9822823
      />
    </aside>
    <aside id="form">
      <ContactForm />
    </aside>
  </main>
</template>

<script>
import GoogleMap from '@/components/GoogleMap'
import ContactForm from '@/components/ContactForm'
export default {
  title () {
    return `Andi-isoliertechnik kontakt`
  },
  name: 'kontakt',
  components: {
    GoogleMap,
    ContactForm
  }

}
</script>

<style scoped>
main {
  display:grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: auto auto;
  grid-template-areas:
    "info form"
    "map form";
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
@media (max-width: 768px) {
  article {
    grid-template-columns: auto;
    grid-template-areas:
      "info"
      "form"
      "map";
  }
}
</style>
