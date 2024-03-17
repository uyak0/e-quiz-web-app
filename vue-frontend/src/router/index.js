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

    if (inClass.data.status) {
      return true
    }
  }
  catch (error) {
    console.log(error) 
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
      path: '/login', 
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (auth.status) {
          if (auth.role === 'student') return { name: 'studentHome', params: { userId: auth.id } }
          else if (auth.role === 'teacher') return { name: 'teacherHome', params: { userId: auth.id } }
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
              beforeEnter: async (to, from) => {
                const auth = await checkAuth()
                const inClass = await inClassroom(to.params.classroomId)
                if (auth.status && inClass.status) {
                  return true
                } 
              },
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
      path: '/student/:userId',
      name: 'student',
      meta: { role: 'student' },
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (Number(to.params.userId) !== auth.id) {
          return { name: 'studentHome', params: { userId: auth.id } }
        }
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
        },
        {
          path: 'profile',
          name: 'studentProfile',
          component: () => import('@/views/UserProfileView.vue'),
          meta: {
            title: 'Profile'
          }
        }
      ]
    },
    {
      path: '/teacher/:userId',
      name: 'teacher',
      meta: { role: 'teacher' },
      beforeEnter: async (to, from) => {
        const auth = await checkAuth()
        if (Number(to.params.userId) !== auth.id) {
          return { name: 'teacherHome', params: { userId: auth.id } }
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
        {
          path: 'profile',
          name: 'teacherProfile',
          component: () => import('@/views/UserProfileView.vue'),
          meta: {
            title: 'Profile'
          }
        }

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
