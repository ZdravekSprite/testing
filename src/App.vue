<template>
  <div id="app">
    <Header />
    <Menu />
    <router-view/>
    <Footer />
  </div>
</template>

<script>
import Header from '@/components/Header'
import Menu from '@/components/Menu'
import Footer from '@/components/Footer'
export default {
  name: 'app',
  components: {
    Header,
    Menu,
    Footer
  }
}
</script>
