const routes = [
    {
      path:'/login',
      name:'login',
      component: () => import('../views/login.vue'),
    },
    
    {
      path: '/',
      name: 'layout',
      component: () => import('../layout/admin.vue'),
      children:[
        {
          path:'/',
          name:'index',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/index.vue')
        },
        {
          path:'/user',
          name:'user',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/user.vue')
        },
        {
          path:'/category',
          name:'category',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/category.vue')
        },
        {
          path:'/category/tree',
          name:'categoryTree',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/categoryTree.vue')
        },
        {
          path:'/goods',
          name:'goods',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/goods.vue')
        },
        {
          path:'/comment',
          name:'comment',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/comment.vue')
        },
        {
          path:'/order',
          name:'order',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/order.vue')
        },
        {
          path:'/advert',
          name:'advert',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/advert.vue')
        },
        {
          path:'/system',
          name:'system',
          meta:{
            isLogin:true,
          },
          component:() =>import('../views/system.vue')
        },
      ]
    },
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (about.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
    // }
  ]

  export default routes