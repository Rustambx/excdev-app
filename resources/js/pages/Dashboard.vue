<script setup>
import { ref, onMounted } from 'vue'
import api from '@/axios'
import RecentTransactions from '../components/RecentTransactions.vue'

const balance = ref(0)
const recentTransactions = ref([])

const fetchData = async () => {
    const res = await api.get('/balance')
    console.log(res.data)
    balance.value = res.data.balance
    recentTransactions.value = res.data.transactions
}

onMounted(() => {
    const token = localStorage.getItem('token')
    if (!token) return

    fetchData()
    setInterval(() => {
        if (localStorage.getItem('token')) {
            fetchData()
        }
    }, 10000)
})
</script>

<template>
    <div class="container mt-4">
        <h2>Баланс: {{ balance }}</h2>
        <RecentTransactions :transactions="recentTransactions" />
    </div>
</template>
