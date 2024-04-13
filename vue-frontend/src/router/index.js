import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

const API = import.meta.env.VITE_LARAVEL_API

async function checkAuth(){
  const auth = await axios.get(API + 'auth/check-authentication')
  return auth.data
}

async function inClassroom(id) {
  try {
    const inClass = await axios.get(API + 'user/in-classroom', {
      params: {
        classroom_id: id
      }
    }) 

    if (inClass.data.status) return true 
  }
  catch (error) {
    console.log(error) 
  }
}

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
      meta: { 
        requiresAuth: false,
        title: 'E-Quizz'
      }
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('@/views/AboutView.vue'),
      meta: {
        requiresAuth: false,
        title: 'E-Quizz - About Us'
      }
    },
    {
      path: '/:userRole/:userId/chatroom',
      name: 'Chatroom',
      component: ()=> import('@/views/ChatroomView.vue'),
      meta: {
        title: 'Chatroom'
      },
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (auth.status) {
          return true
        }
      }
    },
    {
      path: '/login', 
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (auth.status) 
          return { name: 'userHome', params: { userRole: auth.role, userId: auth.id } }
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
        requiresAuth: false,
        title: 'Sign Up'
      }
    },
    {
      path: '/forgot-password',
      name: 'forgotPassword',
      component: () => import('@/views/ForgotPasswordView.vue') ,
      meta: {
        requiresAuth: false,
        title: 'Forgot Password'
      }
    },
    {
      path: '/:userRole/:userId',
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (auth.status && Number(to.params.userId) !== auth.id) {
          return { name: to.name, params: { userRole: auth.role, userId: auth.id } }
        }
      },
      children: [
        {
          path: 'home',
          name: 'userHome',
          component: () => import('@/views/UserHomeView.vue'),
          meta: {
            title: 'Home'
          }
        },
        {
          path: 'profile',
          name: 'userProfile',
          component: () => import('@/views/UserProfileView.vue'),
          meta: {
            title: 'Profile'
          }
        },
        {
          path: 'classroom',
          children: [
            {
              path: ':classroomId',
              beforeEnter: async (to, from) => {
                const inClass = await inClassroom(to.params.classroomId)
                if (inClass.status) {
                  return true 
                } 
              },
              children: [
                {
                  path: '',
                  name: 'classroom',
                  component: () => import('@/views/ClassroomView.vue'),
                  meta: { title: 'Classroom' },
                },
                {
                  path: 'quiz',
                  children: [
                    {
                      path: 'create',
                      name: 'createQuiz',
                      component: () => import('@/views/CreateQuizView.vue'),
                      meta: { title: 'Create Quiz' }
                    },
                    {
                      path: ':quizId',
                      name: 'quiz',
                      component: () => import('@/views/QuizView.vue'),
                      meta: { title: 'Quiz' }
                    },
                    {
                      path: 'result',
                      name: 'quizResult',
                      component: () => import('@/views/QuizResultView.vue'),
                      meta: { title: 'Quiz Result' },
                    }
                  ]
                },
              ]
            },
          ]
        },
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
