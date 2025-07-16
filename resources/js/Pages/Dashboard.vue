<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  boards: Array,
  stats: Object,
  recentActivities: Array,
});
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout>
    <template #header>
      <h2 class="text-2xl font-bold text-white">Olá, {{ $page.props.auth.user.name }} 👋</h2>
      <p class="text-gray-400">Pronto para organizar seu dia com impacto?</p>
    </template>

    <div class="py-8 px-4 space-y-8">
      <!-- Cards de estatísticas -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-gray-800 rounded-lg p-6 text-center shadow">
          <h3 class="text-gray-400">Quadros</h3>
          <p class="text-3xl text-white font-bold">{{ stats.boards }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 text-center shadow">
          <h3 class="text-gray-400">Colunas</h3>
          <p class="text-3xl text-white font-bold">{{ stats.columns }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 text-center shadow">
          <h3 class="text-gray-400">Cartões</h3>
          <p class="text-3xl text-white font-bold">{{ stats.cards }}</p>
        </div>
      </div>

      <!-- Quadros recentes -->
      <div>
        <h3 class="text-xl font-semibold text-white mb-4">Meus Quadros</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <div v-for="board in boards" :key="board.id" class="bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg transition">
            <h4 class="text-lg font-bold text-white mb-2">{{ board.name }}</h4>
            <p class="text-gray-400 text-sm">{{ board.description }}</p>
            <Link :href="route('boards.show', board.id)" class="text-blue-400 mt-2 inline-block hover:underline">
              Ver Quadro →
            </Link>
          </div>
        </div>
      </div>

      <!-- Atividades recentes -->
      <div>
        <h3 class="text-xl font-semibold text-white mb-4">Atividades Recentes</h3>
        <ul class="space-y-2">
          <li v-for="activity in recentActivities" :key="activity.id" class="bg-gray-800 rounded p-4 text-gray-300">
            {{ activity.description }} — <span class="text-gray-500 text-xs">{{ activity.created_at }}</span>
          </li>
        </ul>
      </div>

      <!-- Botão criar novo quadro -->
      <div class="text-center">
        <Link
          href="/boards/create"
          class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-blue-700 transition"
        >
          + Criar Novo Quadro
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
