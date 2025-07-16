<template>
  <div>
    <h1>Criar Empresa</h1>
    <form @submit.prevent="submit">
      <div>
        <label for="name">Nome</label>
        <input id="name" v-model="form.name" required />
      </div>
      <div>
        <label for="slug">Slug</label>
        <input id="slug" v-model="form.slug" required />
      </div>
      <div>
        <label for="description">Descrição</label>
        <textarea id="description" v-model="form.description"></textarea>
      </div>
      <button type="submit" :disabled="processing">Salvar</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const form = reactive({
  name: '',
  slug: '',
  description: '',
});

const processing = ref(false);

function submit() {
  processing.value = true;

  router.post(route('companies.store'), form, {
    onFinish: () => {
      processing.value = false;
    },
  });
}
</script>
