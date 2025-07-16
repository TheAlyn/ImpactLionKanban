<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import Draggable from 'vuedraggable'
import AppLayout from '@/Layouts/AppLayout.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DialogModal from '@/Components/DialogModal.vue'
import { ref, computed } from 'vue'

// Props
const { board } = defineProps({ board: Object })

// == Colunas ==
const creatingColumn = ref(false)
const columnName = ref('')
const columnForm = useForm({
  name: '',
  board_id: board.id,
})

// Abre/cancela formulário de nova coluna
function openCreateColumn() { creatingColumn.value = true; columnName.value = '' }
function cancelCreateColumn() { creatingColumn.value = false; columnName.value = '' }

// Submete nova coluna
function submitCreateColumn() {
  if (!columnName.value.trim()) return
  columnForm.name = columnName.value

  console.log('Submitting column:', columnForm.name)

  columnForm.post(route('columns.store'), {
    onSuccess: () => {
      creatingColumn.value = false
      columnName.value = ''
    }
  })
}

// == Cartões ==
const showCardModal = ref(false)
const selectedColumn = ref(null)
const cardForm = useForm({
  title: '',
  description: '',
  position: 0,
  due_date: '',
  priority: 'medium',  // low | medium | high
  color: '#FFFFFF',
  icon: '',
  background_image: '',
  board_id: board.id,
  column_id: null,
})

// Abre modal de adicionar/editar cartão
function addCard(column) {
  selectedColumn.value = column.id
  cardForm.reset({
    title: '',
    description: '',
    position: column.cards.length,
    due_date: '',
    priority: 'medium',
    color: '#FFFFFF',
    icon: '',
    background_image: '',
    board_id: board.id,
  })
  cardForm.column_id = column.id
  showCardModal.value = true
}

// Fecha modal
function closeCardModal() {
  showCardModal.value = false
}

// Submete cartão
function submitCard() {
  console.log('Payload cards.store:', {
    title: cardForm.title,
    description: cardForm.description,
    position: cardForm.position,
    due_date: cardForm.due_date,
    priority: cardForm.priority,
    color: cardForm.color,
    icon: cardForm.icon,
    background_image: cardForm.background_image,
    board_id: cardForm.board_id,
    column_id: cardForm.column_id,
  })
  cardForm.post(route('cards.store'), {
    onSuccess: () => {
      showCardModal.value = false
    }
  })
}

// Drag & Drop: atualiza posição e coluna via API
function onCardDrop(evt, column) {
  // tenta achar id da coluna de origem (se precisar)
  const fromColEl = evt.from.closest('[data-column-id]')
  const fromCol = fromColEl?.dataset.columnId ?? null
  console.log('Drag start column:', fromCol, '→ drop column:', column.id)

  // lê o card.id direto do elemento arrastado
  const movedCardId = evt.item.dataset.cardId
  if (!movedCardId) {
    console.error('Não achei data-card-id no elemento dropado')
    return
  }
  console.log('Card que será movido (ID):', movedCardId)

  // calcula nova posição
  const newPos = evt.newIndex
  console.log('Nova posição dentro da coluna:', newPos)

  // chama a API para atualizar coluna e posição
  // chama a sua rota cards.move (POST /cards/move)
  router.post(
    route('cards.move'),
    {
      card_id: movedCardId,
      column_id: column.id,
      position: newPos,
    },
    {
      preserveState: true,
      onSuccess: () => {
        console.log('Movido com sucesso, recarregando board…')
        router.reload()
      },
      onError: (errors) => console.error('Erro ao mover:', errors),
    }
  )
}

// Cor do quadro
const boardColor = computed(() => board.color || '#ffffff')
</script>

<template>
  <AppLayout>

    <Head :title="board.name" />

    <!-- Modal: Adicionar Cartão -->
    <DialogModal :show="showCardModal" @close="closeCardModal">
      <template #title>Adicionar / Editar Cartão</template>
      <template #content>
        <div class="space-y-4">
          <input v-model="cardForm.title" type="text" placeholder="Título da tarefa"
            class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500" />
          <textarea v-model="cardForm.description" placeholder="Descrição (opcional)"
            class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500" />
          <div class="grid grid-cols-2 gap-4">
            <select v-model="cardForm.priority"
              class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring focus:ring-blue-500">
              <option value="low">Baixa</option>
              <option value="medium">Média</option>
              <option value="high">Alta</option>
            </select>
            <input v-model="cardForm.due_date" type="date"
              class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring focus:ring-blue-500" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <input v-model="cardForm.color" type="color" class="w-full h-10 p-0 border-0 rounded cursor-pointer" />
            <input v-model="cardForm.icon" type="text" placeholder="Ícone (opcional)"
              class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500" />
          </div>
          <input v-model="cardForm.background_image" type="text" placeholder="URL da imagem de fundo (opcional)"
            class="w-full px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500" />
        </div>
      </template>
      <template #footer>
        <button @click="closeCardModal"
          class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600 mr-2">Cancelar</button>
        <button @click="submitCard" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
      </template>
    </DialogModal>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
      <!-- Cabeçalho -->
      <header class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold" :style="{ color: boardColor }">{{ board.name }}</h1>
          <p class="text-sm text-gray-400">
            Empresa:
            <span v-if="board.tenant">{{ board.tenant.name }}</span>
            <span v-else class="italic text-red-400">Nenhuma empresa atrelada a este quadro</span>
          </p>
        </div>
        <Link :href="route('boards.index')" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 transition">Voltar
        </Link>
      </header>

      <!-- Container de colunas -->
      <section class="columns-container flex space-x-4 overflow-x-auto hide-scrollbar min-h-[400px] pb-4 pr-16">
        <!-- Para cada coluna existente -->
        <template v-if="board.columns?.length">
          <div v-for="column in board.columns" :key="column.id" :data-column-id="column.id"
            class="bg-gray-800 rounded-md p-4 w-72 flex-shrink-0 flex flex-col max-h-[600px] relative">
            <!-- Cabeçalho da coluna -->
            <header class="mb-4 flex items-center justify-between">
              <h2 class="font-semibold text-lg text-white truncate">{{ column.name }}</h2>
              <Dropdown align="right" :contentClasses="['py-1', 'bg-gray-800', 'text-gray-200']">
                <template #trigger>
                  <button class="text-white text-xl px-2 hover:bg-white/10 rounded">⋮</button>
                </template>
                <template #content>
                  <ul class="divide-y divide-gray-700">
                    <li>
                      <button @click="addCard(column)" class="block w-full text-left px-4 py-2 hover:bg-gray-700">➕
                        Adicionar cartão</button>
                    </li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">📋 Copiar Lista</button></li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">↔️ Mover Lista</button></li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">📤 Mover todos os
                        cartões</button></li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">👀 Seguir</button></li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">🎨 Alterar cor
                        (Upgrade)</button></li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">🗄️ Arquivar Lista</button>
                    </li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">🗑️ Excluir Lista</button>
                    </li>
                    <li><button class="block w-full text-left px-4 py-2 hover:bg-gray-700">📦 Arquivar todos os
                        cartões</button></li>
                  </ul>
                </template>
              </Dropdown>
            </header>

            <!-- Cards com drag & drop -->
            <Draggable v-model="column.cards" :itemKey="'id'" :group="{ name: 'cards', pull: true, put: true }"
              @end="evt => onCardDrop(evt, column)" class="flex-grow overflow-y-auto space-y-3">
              <template #item="{ element: card }">
                <div :data-card-id="card.id" class="rounded-md p-3 shadow cursor-pointer transition"
                  :style="{ backgroundColor: card.color || '#2d3748', color: card.text_color || '#ffffff' }"
                  @mouseover="$el.classList.add('hover:bg-gray-600')"
                  @mouseout="$el.classList.remove('hover:bg-gray-600')">
                  <h3 class="font-semibold text-white">{{ card.title }}</h3>
                  <p class="text-gray-300 text-sm truncate" v-if="card.description">{{ card.description }}</p>
                  <small class="text-gray-400">Prioridade: {{ card.priority }}</small>
                </div>
              </template>
            </Draggable>
            <p v-if="!column.cards.length" class="text-gray-500 text-sm italic">Nenhum card nesta lista.</p>

            <!-- Botão “+ Adicionar um cartão” -->
            <button @click="addCard(column)"
              class="flex items-center mt-2 text-sm text-white/90 hover:bg-white/10 px-4 py-2 rounded transition w-full">
              <span class="text-xl mr-2">+</span> Adicionar um cartão
            </button>
          </div>
        </template>

        <!-- Se não houver colunas, só aparece o botão de adicionar -->
        <template v-else>
          <div class="w-72 flex-shrink-0 flex items-center justify-center">
            <button @click="openCreateColumn"
              class="flex items-center text-sm text-white/90 hover:bg-white/10 px-4 py-2 rounded transition">
              <span class="text-xl mr-2">+</span> Adicionar lista
            </button>
          </div>
        </template>

        <!-- Botão “Adicionar outra lista” sempre visível -->
        <div class="w-72 flex-shrink-0 flex flex-col items-start justify-start">
          <template v-if="!creatingColumn">
            <button @click="openCreateColumn"
              class="flex items-center text-sm text-white/90 hover:bg-white/10 px-4 py-2 rounded transition w-full">
              <span class="text-xl mr-2">+</span>
              {{ board.columns.length ? 'Adicionar outra lista' : 'Adicionar lista' }}
            </button>
          </template>
          <template v-else>
            <div class="w-full bg-gray-900 rounded-md p-4">
              <input v-model="columnName" type="text" placeholder="Nome da lista..."
                class="w-full mb-2 px-3 py-2 rounded bg-gray-800 text-white border border-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500" />
              <div class="flex space-x-2">
                <button @click="submitCreateColumn"
                  class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">Adicionar Lista</button>
                <button @click="cancelCreateColumn" class="text-white text-xl font-bold px-2"
                  title="Cancelar">✕</button>
              </div>
            </div>
          </template>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<style scoped>
.hide-scrollbar {
  -ms-overflow-style: none;
  /* IE e Edge */
  scrollbar-width: none;
  /* Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;
  /* Chrome, Safari e Opera */
}

.columns-container {
  position: relative;
}

.columns-container::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 6rem;
  height: 100%;
  pointer-events: none;
  /* Fade que indica que há conteúdo à direita */
  background: linear-gradient(to right,
      rgba(31, 41, 55, 0) 0%,
      rgba(31, 41, 55, 0.9) 100%);
}
</style>
