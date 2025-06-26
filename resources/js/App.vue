<script setup>
import { useRouter, useRoute } from 'vue-router'
import api from '@/axios'
import { ref, watch } from 'vue'

const router = useRouter()
const route = useRoute()

const isAuthenticated = ref(false)

const checkAuth = async () => {
    try {
        await api.get('/user')
        isAuthenticated.value = true
    } catch {
        isAuthenticated.value = false
    }
}

const logout = async () => {
    await api.post('/logout')
    localStorage.removeItem('token')
    isAuthenticated.value = false
    router.push('/login')
}

watch(
    () => route.path,
    (newPath) => {
        if (newPath !== '/login') {
            checkAuth()
        } else {
            isAuthenticated.value = false
        }
    },
    { immediate: true }
)
</script>

<template>
    <div>
        <nav class="navbar navbar-light bg-light mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Excdev</a>
                <ul v-if="isAuthenticated" class="nav">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" :class="{ active: route.path === '/' }">Главная</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/transactions" class="nav-link" :class="{ active: route.path === '/transactions' }">История операций</router-link>
                    </li>
                </ul>
                <button v-if="isAuthenticated" class="btn btn-outline-danger btn-sm" @click="logout">Выйти</button>
            </div>
        </nav>
        <div class="container">
            <router-view />
        </div>
    </div>
</template>
