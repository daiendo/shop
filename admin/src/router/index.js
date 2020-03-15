import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './router.js'
import Storage from '../store/storage.js'

Vue.use(VueRouter)



const router = new VueRouter({
  // mode: 'history',
  linkActiveClass: "active",
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to,from,next)=>{
  let title = '网站后台-'
  if(to.name && to.name != ''){
    title += to.name

  }
  document.title = title;
  if(to.meta.isLogin){
    let token = Storage.sessionGet('token');
    if(!token){
      next('/login')
    }else{
      next()
    }
  }else{
    next()
  }
})

export default router
