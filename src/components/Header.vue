<template>
  <header role="banner">
    <h1><router-link to="/">{{ h1 }}</router-link></h1>
    <h2>{{ h2 }}</h2>
    <form role="search" method="get" action="http://andis.hr">
      <span>Search for:</span>
      <input type="text" placeholder="Search...." value="" name="s">
    </form>
    <div id="social-icons"></div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  props: {
    h1: String,
    h2: String
  }
}
</script>
