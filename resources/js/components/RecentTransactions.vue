<script setup>
defineProps(['transactions'])
</script>

<template>
    <div>
        <h4>Последние 5 операции</h4>
        <ul class="list-group">
            <li v-for="transaction in transactions" :key="transaction.id" class="list-group-item d-flex justify-content-between">
                <span>{{ transaction.description }}</span>
                <span :class="{'text-success': transaction.type === 'credit', 'text-danger': transaction.type === 'debit'}">
          {{ transaction.type === 'credit' ? '+' : '-' }}{{ transaction.amount }}
        </span>
            </li>
        </ul>
    </div>
</template>
