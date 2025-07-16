<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// 1. Desestrutura aqui:
const { boards } = defineProps({
  boards: Array,
})

// normaliza a cor para sempre começar com '#'
function normalizeColor(c) {
  if (!c) return '#ffffff'
  return c.startsWith('#') ? c : `#${c}`
}

// Função para deletar quadro com confirmação
function deleteBoard(id) {
  if (confirm('Tem certeza que deseja excluir este quadro?')) {
    router.delete(route('boards.destroy', id))
  }
}
</script>

<template>
  <AppLayout>
    <Head title="Meus Quadros" />

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 text-white">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">📋 Meus Quadros</h1>
        <Link
          :href="route('boards.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          Criar Novo Quadro
        </Link>
      </div>

      <div v-if="boards.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="board in boards"
          :key="board.id"
          class="bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between"
          :style="{ borderLeft: `4px solid ${normalizeColor(board.color)}` }"
        >
          <div>
            <h2 class="text-lg font-semibold mb-1">{{ board.name }}</h2>
            <p class="text-sm text-gray-400 mb-2">{{ board.description || 'Sem descrição' }}</p>

            <!-- 2. Exibição das contagens -->
            <p class="text-xs text-gray-500 mb-4 leading-tight">
              Listas: {{ board.columns_count }} Total<br>
              Cartões: {{ board.cards_count }} Total
            </p>
          </div>

          <div class="flex justify-between">
            <Link
              :href="route('boards.edit', board.id)"
              class="inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm"
            >
              Editar
            </Link>
            <button
              @click="deleteBoard(board.id)"
              class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm"
            >
              Excluir
            </button>
          </div>
        </div>
      </div>

      <div v-else class="bg-gray-800 p-6 rounded-md text-center">
        <p class="mb-4">😕 Nenhum quadro encontrado no momento.</p>
        <Link
          :href="route('boards.create')"
          class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          Criar Primeiro Quadro
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
