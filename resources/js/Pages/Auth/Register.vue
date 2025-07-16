<template>
  <Head title="Registrar-se | Kanban Impact - Lion" />

  <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-gray-900 via-gray-800 to-black px-4 py-12">
    <!-- Logo -->
    <img src="/logo.png" alt="Logo" class="w-20 mb-6" />

    <!-- Formulário -->
    <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">
        Criar nova conta 📝
      </h2>

      <form @submit.prevent="submit">
        <!-- Name -->
        <div class="mb-4">
          <label class="block text-gray-300 mb-1" for="name">Nome</label>
          <input
            v-model="form.name"
            id="name"
            type="text"
            class="w-full px-4 py-2 rounded bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-blue-500"
            required
            autofocus
          />
          <InputError class="mt-1" :message="form.errors.name" />
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-300 mb-1" for="email">Email</label>
          <input
            v-model="form.email"
            id="email"
            type="email"
            class="w-full px-4 py-2 rounded bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-blue-500"
            required
          />
          <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label class="block text-gray-300 mb-1" for="password">Senha</label>
          <input
            v-model="form.password"
            id="password"
            type="password"
            class="w-full px-4 py-2 rounded bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-blue-500"
            required
          />
          <InputError class="mt-1" :message="form.errors.password" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
          <label class="block text-gray-300 mb-1" for="password_confirmation">Confirme a Senha</label>
          <input
            v-model="form.password_confirmation"
            id="password_confirmation"
            type="password"
            class="w-full px-4 py-2 rounded bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-blue-500"
            required
          />
          <InputError class="mt-1" :message="form.errors.password_confirmation" />
        </div>

        <!-- Terms -->
        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mb-6 text-sm text-gray-400">
          <label class="flex items-center">
            <input type="checkbox" v-model="form.terms" required class="mr-2">

            Eu concordo com os
            <a :href="route('terms.show')" target="_blank" class="underline ml-1 hover:text-blue-400">Termos de Serviço</a>
            e
            <a :href="route('policy.show')" target="_blank" class="underline ml-1 hover:text-blue-400">Política de Privacidade</a>.
          </label>
          <InputError class="mt-1" :message="form.errors.terms" />
        </div>

        <!-- Botão -->
        <button
          type="submit"
          class="w-full py-3 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
          :disabled="form.processing"
        >
          Criar Conta
        </button>
      </form>

      <p class="mt-4 text-center text-gray-400 text-sm">
        Já tem uma conta?
        <Link href="/login" class="text-blue-400 hover:underline">Entrar</Link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>
