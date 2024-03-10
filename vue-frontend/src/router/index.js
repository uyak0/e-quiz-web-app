import { createRouter, createWebHistory } from 'vue-router'

function isAuthenticated() {
  const tokenExists = localStorage.getItem('token') ? true : false
  if (tokenExists){
    return true
  } else {
    return false
  }
}
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
      beforeEnter: (to, from) => {
        if (isAuthenticated) {
          const userRole = localStorage.getItem('user_role')
          const userID = localStorage.getItem('user_id')
          if (userRole === 'teacher') {
            return { path: 'teacher/' + userID + '/home' }
          } 
          else if (userRole === 'student') {
            return { path: 'student/' + userID + '/home' }
          }
        }
      },
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
      path: '/student/:id',
      name: 'student',
      beforeEnter: (to, from) => {
        if (!isAuthenticated) {
          return { name: 'login' }
        }
        else { 
          const userRole = localStorage.getItem('user_role')
          const userID = localStorage.getItem('user_id')
          if (userRole !== 'student' || userID !== to.params.id) {
            return  { path: 'student/' + userID + '/home' }
          }
        }
      },
      children: [
        {
          path: 'home',
          name: 'studentHome',
          component: () => import('@/views/StudentHomeView.vue'),
          meta: {
            title: 'Home'
          }
        },
        {
          path: 'profile',
          name: 'studentProfile',
          component: () => import('@/views/StudentProfileView.vue'),
          meta: {
            title: 'Profile'
          }
        },
        {
          path: 'classroom/join',
          name: 'joinClassroom',
          component: () => import('@/views/JoinClassroomView.vue'),
          meta: {
            title: 'Join A Classroom'
          }
        }
      ]
    },
  ]
})

// Navigation Guard
// router.beforeEach((to, from) => {
//   if (!isAuthenticated && to.name !== 'login') {
//     return { name: 'login' }
//   } else {
//     return true
//   }
// })

// Adds title tag to each route
router.beforeEach((to, from) => {
  document.title = to.meta?.title ?? 'E-Quizz';
})

export default router
