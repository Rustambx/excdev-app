<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/axios'

const transactions = ref([])
const search = ref('')
const sortKey = ref('created_at')
const sortAsc = ref(false)

const fetchTransactions = async () => {
    const res = await api.get('/transactions')
    transactions.value = res.data
}

onMounted(fetchTransactions)

const filteredTransactions = computed(() => {
    return transactions.value
        .filter(transaction => transaction.description.toLowerCase().includes(search.value.toLowerCase()))
        .sort((a, b) => {
            let cmp = a[sortKey.value] < b[sortKey.value] ? -1 : 1
            return sortAsc.value ? cmp : -cmp
        })
})

const sortBy = (key) => {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value
    } else {
        sortKey.value = key
        sortAsc.value = true
    }
}
</script>

<template>
    <div class="container mt-4">
        <h2>История операций</h2>

        <input v-model="search" class="form-control mb-3" placeholder="Поиск по описанию..." />

        <table class="table table-striped">
            <thead>
            <tr>
                <th @click="sortBy('created_at')" style="cursor: pointer;">
                    Дата
                    <span v-if="sortKey === 'created_at'">
                    <span v-if="sortAsc">▲</span>
                    <span v-else>▼</span>
                  </span>
                </th>
                <th>Тип</th>
                <th>Описание</th>
                <th>Сумма</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="transaction in filteredTransactions" :key="transaction.id">
                <td>{{ transaction.created_at }}</td>
                <td>{{ transaction.type }}</td>
                <td>{{ transaction.description }}</td>
                <td>{{ transaction.amount }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
