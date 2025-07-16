<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    companies: Array,
})
</script>

<template>

    <Head title="Minhas Empresas" />

    <AppLayout>
        <template #header>
            <h2 class="text-2xl font-bold text-white">Minhas Empresas</h2>
            <p class="text-gray-400">Aqui estão as empresas que você gerencia ou faz parte.</p>
        </template>

        <div class="py-8 px-4 space-y-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow text-white">
                <div v-if="companies.length > 0">
                    <ul>
                        <li v-for="company in companies" :key="company.id" class="mb-2 border-b border-gray-700 pb-2">
                            {{ company.name }}
                        </li>
                    </ul>
                </div>

                <div v-else class="bg-gray-800 p-6 rounded-md text-center">
                    <p class="mb-4">😕 Nenhuma empresa disponível no momento.</p>
                    <p class="mb-4">Entre em contato com o suporte para criar uma empresa.</p>
                    <p class="text-sm text-gray-400">E-mail: <a href="mailto:suporte@impact-lion.com"
                            class="text-blue-400 underline hover:text-blue-300">suporte@impact-lion.com</a></p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
