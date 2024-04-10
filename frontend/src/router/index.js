import { useAuthStore } from '@/store';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/HomeView.vue'),
        meta: { requiresAuth: true },

    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/LoginView.vue'),
    },
    {
        path: '/siswa',
        name: 'siswa',
        component: () => import('../views/pages/siswa/SiswaView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/siswa/create',
        name: 'siswa-create',
        component: () => import('../views/pages/siswa/SiswaCreateView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/siswa/edit/:id',
        name: 'siswa-edit',
        component: () => import('../views/pages/siswa/SiswaEditView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/tutor',
        name: 'tutor',
        component: () => import('../views/pages/tutor/TutorView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/:catchAll(.*)',
        name: 'not-found',
        // route level code-splitting
        // this generates a separate chunk (404View.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('../views/404View.vue')
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    await authStore.validateAuth();

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if(!authStore.getAuthStatus) {
            next('/login');
        } else {
            next();
        }
    } else {
        if (to.path === '/login' && authStore.getAuthStatus) {
            next('/');
        } else {
            next();
        }
    }

});


export default router
