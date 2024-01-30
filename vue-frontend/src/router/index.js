import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import LoginView from '../views/LoginView.vue'
import SignUpView from '../views/SignUpView.vue'
import CreateQuizView from '../views/CreateQuizView.vue'

// function AuthGuard(to, from, next) {
//   if (!sessionStorage.getItem('token')) {
//     next({ name: 'login' })
//   } else {
//     next()
//   }
// }



const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { 
        title: 'E-Quizz'
      }
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: AboutView,
      meta: {
        title: 'E-Quizz - About Us'
      }
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        title: 'Login'
      }
    },
    {
      path: '/signup',
      name: 'sign up',
      component: SignUpView,
      meta: {
        title: 'Sign Up'
      }
    },
    {
      path: '/createquiz',
      name: 'create quiz',
      component: CreateQuizView, 
    }
  ]
})

// Adds title tag to each route
router.beforeEach((to, from) => {
  document.title = to.meta?.title ?? 'E-Quizz';
})

export default router
