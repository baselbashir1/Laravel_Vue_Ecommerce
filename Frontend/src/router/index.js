import { createRouter, createWebHistory } from "vue-router"
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'
import ResetPassword from '../views/ResetPassword.vue'
import RequestPassword from '../views/RequestPassword.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/request-password',
        name: 'requestPassword',
        component: RequestPassword
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: ResetPassword
    }
]

const router = createRouter({
    history: createWebHistory(), // domain.com#/users
    routes
})

export default router