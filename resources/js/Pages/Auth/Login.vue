<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'));
};
</script>

<template>
  <Head title="Login" />

  <div
    class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-gray-900 via-gray-800 to-black px-4 py-12"
  >
    <div
      class="relative w-full max-w-md bg-gray-900 rounded-lg p-8 shadow-lg text-white"
    >
      <img src="/logo.png" alt="Logo" class="w-20 mx-auto mb-6" />

      <h1 class="text-3xl font-extrabold text-center mb-6">
        Faça seu login
      </h1>

      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="email" class="block mb-1 text-gray-300 font-semibold"
            >Email</label
          >
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
            class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <p v-if="form.errors.email" class="mt-1 text-red-500 text-sm">
            {{ form.errors.email }}
          </p>
        </div>

        <div>
          <label
            for="password"
            class="block mb-1 text-gray-300 font-semibold"
            >Senha</label
          >
          <input
            id="password"
            type="password"
            v-model="form.password"
            required
            autocomplete="current-password"
            class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <p v-if="form.errors.password" class="mt-1 text-red-500 text-sm">
            {{ form.errors.password }}
          </p>
        </div>

        <div class="flex items-center">
          <input
            id="remember"
            type="checkbox"
            v-model="form.remember"
            class="mr-2"
          />
          <label for="remember" class="text-gray-400 select-none">
            Lembrar-me
          </label>
        </div>

        <div>
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-2 rounded disabled:opacity-50"
          >
            Entrar
          </button>
        </div>
      </form>

      <div class="mt-4 text-center text-gray-400 text-sm">
        <Link href="/register" class="underline hover:text-blue-400">
          Ainda não tem conta? Cadastre-se
        </Link>
      </div>
    </div>
  </div>
</template>
