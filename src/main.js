import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

import App from './App'
import router from './router'
import titleMixin from './mixins/titleMixin'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false

Vue.mixin(titleMixin)
Vue.use(BootstrapVue)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
