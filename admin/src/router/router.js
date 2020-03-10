const routes = [
    
    {
      path: '/',
      name: 'layout',
      component: () => import('../layout/admin.vue'),
      children:[
        {
          path:'/',
          name:'index',
          component:() =>import('../views/index.vue')
        },
        {
          path:'/user',
          name:'user',
          component:() =>import('../views/user.vue')
        },
        {
          path:'/category',
          name:'category',
          component:() =>import('../views/category.vue')
        },
        {
          path:'/category/tree',
          name:'categoryTree',
          component:() =>import('../views/categoryTree.vue')
        },
        {
          path:'/goods',
          name:'goods',
          component:() =>import('../views/goods.vue')
        },
        {
          path:'/comment',
          name:'comment',
          component:() =>import('../views/comment.vue')
        }
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