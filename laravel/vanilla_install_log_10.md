# Laravel Vue
### resources\views\dashboard.blade.php
```php
          <div id="app">
            <example-component></example-component>
          </div>
```
```bash
npm install
npm install vue
npm install vue-template-compiler vue-loader@^15.9.5 --save-dev --legacy-peer-deps
```
### webpack.mix.js
```ts
mix.js('resources/js/app.js', 'public/js').vue()
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ]);
```
### resources\js\app.js
```ts
import Vue from 'vue';
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
const app = new Vue({
  el: '#app',
});
```
### resources\views\dashboard.blade.php
```php
          <div id="app">
            <example-component></example-component>
          </div>
```
### resources\js\components\ExampleComponent.vue
```ts
<template>
  <div>I'm an example component.</div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
```
```bash
npm run dev
php artisan serve
git add .
git commit -am "Laravel Vue v0.10a [laravel]"
```