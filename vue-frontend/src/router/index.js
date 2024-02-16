import { createRouter, createWebHistory } from 'vue-router'
import { HomeView, AboutView, LoginView, SignUpView, CreateQuizView, ForgotPasswordView, StudentHomeView } from '@/views/views.js'

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
      path: '/forgot-password',
      name: 'forgot password',
      component: ForgotPasswordView,
      meta: {
        title: 'Forgot Password'
      }
    },
    {
      path: '/createquiz/mcq',
      name: 'create quiz MCQ',
      component: CreateQuizView, 
    },
    {
      path: '/student-home',
      name: 'student dashboard/home',
      component: StudentHomeView,
      meta: {
        title: 'Dashboard'
      }
    }
  ]
})

// Adds title tag to each route
router.beforeEach((to, from) => {
  document.title = to.meta?.title ?? 'E-Quizz';
})

export default router
