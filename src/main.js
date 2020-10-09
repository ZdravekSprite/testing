import Vue from 'vue'

import App from './App'
import router from './router'
import titleMixin from './mixins/title'
import headMixin from './mixins/head'

Vue.config.productionTip = false

Vue.mixin(titleMixin)
Vue.mixin(headMixin)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
