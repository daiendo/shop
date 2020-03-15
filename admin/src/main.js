import Vue from 'vue'
import App from './App.vue'
import router from './router'
import api from './api/index'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import VueMeta from 'vue-meta';

Vue.use(ViewUI)
Vue.use(api)
Vue.use(VueMeta)

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
