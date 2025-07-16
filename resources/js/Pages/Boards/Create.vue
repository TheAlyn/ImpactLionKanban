<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  tenants: {
    type: Array,
    default: () => [],
  },
})

const form = ref({
  name: '',
  tenant_id: null, // pode ser null
  color: '#ffffff',
  icon: '',
  description: '',
  background_image: '',
  is_favorite: false,
  is_public: false,
  is_archived: false,
  is_template: false,
  is_read_only: false,
  is_collaborative: false,
  is_private: false,
  is_locked: false,
  is_pinned: false,
  is_hidden: false,
  is_archived_by_user: false,
  is_shared: false,
  is_synced: false,
  is_trashed: false,
})

const errors = ref({})

function submit() {
  errors.value = {}

  router.post(route('boards.store'), form.value, {
    onError: (e) => {
      errors.value = e
    }
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Criar Novo Quadro" />

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8 text-white">
      <h1 class="text-2xl font-bold mb-6">Criar Novo Quadro</h1>

      <form @submit.prevent="submit" class="space-y-6 bg-gray-800 p-6 rounded-md shadow">

        <!-- Nome -->
        <div>
          <label for="name" class="block mb-1 font-semibold">Nome <span class="text-red-500">*</span></label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="w-full rounded px-3 py-2 bg-gray-700 border border-gray-600 text-white"
            required
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
        </div>

        <!-- Empresa (Tenant) - aparece só se houver tenants -->
        <div v-if="tenants.length > 0">
          <label for="tenant_id" class="block mb-1 font-semibold">Empresa</label>
          <select
            v-model="form.tenant_id"
            id="tenant_id"
            class="w-full rounded px-3 py-2 bg-gray-700 border border-gray-600 text-white"
          >
            <option value="" disabled>Selecione a empresa (opcional)</option>
            <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
              {{ tenant.name }}
            </option>
          </select>
          <p v-if="errors.tenant_id" class="text-red-500 text-sm mt-1">{{ errors.tenant_id[0] }}</p>
        </div>

        <!-- Cor -->
        <div>
          <label for="color" class="block mb-1 font-semibold">Cor do Quadro</label>
          <input
            v-model="form.color"
            type="color"
            id="color"
            class="w-16 h-10 p-0 border-0 rounded cursor-pointer"
          />
        </div>

        <!-- Ícone -->
        <div>
          <label for="icon" class="block mb-1 font-semibold">Ícone</label>
          <input
            v-model="form.icon"
            type="text"
            id="icon"
            placeholder="Nome do ícone ou URL"
            class="w-full rounded px-3 py-2 bg-gray-700 border border-gray-600 text-white"
          />
        </div>

        <!-- Descrição -->
        <div>
          <label for="description" class="block mb-1 font-semibold">Descrição</label>
          <textarea
            v-model="form.description"
            id="description"
            rows="3"
            class="w-full rounded px-3 py-2 bg-gray-700 border border-gray-600 text-white resize-none"
          ></textarea>
        </div>

        <!-- Flags (booleanos) -->
        <div class="grid grid-cols-2 gap-4">
          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_favorite" />
            <span>Favorito</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_public" />
            <span>Público</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_archived" />
            <span>Arquivado</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_template" />
            <span>Modelo</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_read_only" />
            <span>Somente Leitura</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_collaborative" />
            <span>Colaborativo</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_private" />
            <span>Privado</span>
          </label>

          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="form.is_locked" />
            <span>Bloqueado</span>
          </label>
        </div>

        <!-- Botões -->
        <div class="flex space-x-4 justify-end mt-6">
          <Link
            :href="route('boards.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded"
          >
            Salvar Quadro
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
