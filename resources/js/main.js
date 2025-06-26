import { createApp } from 'vue'
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import api from './axios'
import Login from '@/pages/Login.vue'
import Dashboard from '@/pages/Dashboard.vue'
import Transactions from '@/pages/Transactions.vue'
import {createRouter, createWebHistory} from "vue-router";

const app = createApp(App)

const routes = [
    { path: '/login', component: Login },
    {
        path: '/',
        component: Dashboard,
        beforeEnter: async (to, from, next) => {
            try {
                await api.get('/user')
                next()
            } catch {
                next('/login')
            }
        }
    },
    {
        path: '/transactions',
        component: Transactions,
        beforeEnter: async (to, from, next) => {
            try {
                await api.get('/user')
                next()
            } catch {
                next('/login')
            }
        }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

app.use(router)
app.config.globalProperties.$api = api

app.mount('#app')
