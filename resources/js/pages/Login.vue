<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/axios'

const email = ref('')
const password = ref('')
const router = useRouter()

const login = async () => {
    const res = await api.post('/login', {
        email: email.value,
        password: password.value,
    })
    localStorage.setItem('token', res.data.token)
    router.push('/')
}
</script>

<template>
    <div class="container mt-5">
        <h2>Вход</h2>
        <form @submit.prevent="login">
            <input v-model="email" type="email" class="form-control mb-2" placeholder="Email" />
            <input v-model="password" type="password" class="form-control mb-3" placeholder="Пароль" />
            <button class="btn btn-primary">Войти</button>
        </form>
    </div>
</template>
