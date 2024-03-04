import { createRouter, createWebHistory } from 'vue-router'

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
      component: () => import('@/views/HomeView.vue'),
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
      component: () => import('@/views/AboutView.vue'),
      meta: {
        title: 'E-Quizz - About Us'
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: {
        title: 'Login'
      }
    },
    {
      path: '/signup',
      name: 'signUp',
      component: () => import('@/views/SignUpView.vue'),
      meta: {
        title: 'Sign Up'
      }
    },
    {
      path: '/forgot-password',
      name: 'forgotPassword',
      component: () => import('@/views/ForgotPasswordView.vue') ,
      meta: {
        title: 'Forgot Password'
      }
    },
    {
      path: '/classroom/:id',
      name: 'classroom',
      component: () => import('@/views/ClassroomView.vue'),
      meta: {
        title: 'Classroom'
      },
      children: [
        {
          path: 'quiz',
          name: 'quiz',
          component: () => import('@/views/QuizView.vue'),
          children: [
            {
              // Quizzes creation page
              path: 'create',
              name: 'createQuiz',
              component: () => import('@/views/CreateQuizView.vue'),
              meta: {
                title: 'Create Quiz'
              }
            },
            {
              path: ':id',
              name: 'quiz',
              component: () => import('@/views/QuizView.vue'),
              meta: {
                title: 'Quiz :id'
              }
            }
          ]
        },
      ]
    },
    {
      path: '/student/home',
      name: 'studentHome',
      component: () => import('@/views/StudentHomeView.vue'),
      meta: {
        title: 'Dashboard'
      }
    },
    
  ]
})

// Adds title tag to each route
router.beforeEach((to, from) => {
  document.title = to.meta?.title ?? 'E-Quizz';
})

export default router
