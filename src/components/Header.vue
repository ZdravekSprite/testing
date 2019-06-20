<template>
  <header>
    <h1>{{ h1 }}</h1>
    <h2>{{ h2 }}</h2>
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
