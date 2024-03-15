import { createRouter, createWebHistory } from 'vue-router'
import { getStorageItem } from '@/functions/getStorageItem.js'
import axios from 'axios'
import { ref } from 'vue'

const API = import.meta.env.VITE_LARAVEL_API
const rememberToken = getStorageItem('remember_token')
var authData = ref({})

function isAuthenticated() {
  axios.get(API + 'auth/user')
    .then(response => {
      authData.value = response.data
    })
    .catch(error => {
      console.log(error)
    }) 

  if (authData.value.role) {
    return true
  }
  else {
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
          return false
        }
        console.log(authData.value)
        if (authData.value.role === 'student') {
          return { name: 'studentHome' }
        } else if (authData.value.role === 'teacher') {
          return { name: 'teacherHome' }
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
      path: '/user/:id/profile',
      name: 'userProfile',
      component: () => import('@/views/UserProfileView.vue'),
      meta: {
        title: 'Profile'
      }
    },
    {
      path: '/classroom',
      children: [
        {
          path: ':classroomId',
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
                  // Quizzes creation page
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
                }
              ]
            },
          ]
        },
        {
          path: 'create',
          name: 'createClassroom',
          component: () => import('@/views/CreateClassroomView.vue'),
          meta: {
            title: 'Create Classroom'
          }
        },
      ]
    },
    {
      path: '/student/:id',
      name: 'student',
      beforeEnter: (to, from) => {

      },
      children: [
        {
          path: 'home',
          name: 'studentHome',
          component: () => import('@/views/UserHomeView.vue'),
          meta: {
            title: 'Home'
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
    {
      path: '/teacher/:id',
      name: 'teacher',
      beforeEnter: (to, from) => {
        if (!isAuthenticated) {
          return false 
        }
      },
      children: [
        {
          path: 'home',
          name: 'teacherHome',
          component: () => import('@/views/UserHomeView.vue'),
          meta: {
            title: 'Home'
          }
        },
        // {
        //   path: 'classroom/create',
        //   name: 'createClassroom',
        //   component: () => import('@/views/CreateClassroomView.vue'),
        //   meta: {
        //     title: 'Create A Classroom'
        //   }
        // }
      ]
    },
    // {
    //   path: '/:pathMatch(.*)*',
    //   name: 'notFound',
    //   component: () => import('@/views/NotFoundView.vue'),
    //   meta: {
    //     title: '404 Not Found'
    //   }
    // }
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
