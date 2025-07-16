<template>
  <!-- Define o título da aba -->
  <Head title="Kanban" />

  <div
    class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-black px-4 py-12"
  >
    <!-- Pattern decorativo SVG -->
    <div
      class="absolute inset-0 bg-[url('data:image/svg+xml;utf8,<svg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><rect width=\'1\' height=\'1\' fill=\'%23ffffff\' fill-opacity=\'0.03\'/></svg>')] opacity-20 pointer-events-none"
    ></div>

    <!-- Conteúdo principal -->
    <div class="relative z-10 flex flex-col items-center space-y-4 text-center">
      <!-- Logo -->
      <img src="/logo.png" alt="Logo" class="w-24 md:w-32" />

      <!-- Título -->
      <h1 class="text-3xl md:text-5xl font-extrabold text-white">
        Bem-vindo ao <span class="text-blue-400">Meu Kanban</span> 🚀
      </h1>

      <!-- Subtítulo -->
      <p class="mt-2 max-w-xl text-gray-300">
        Organize suas tarefas, colabore em tempo real e gerencie projetos com uma experiência visual incrível.
      </p>

      <!-- Botões -->
      <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <Link
          v-if="canLogin"
          href="/login"
          class="inline-block rounded-full px-8 py-3 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
        >
          Entrar
        </Link>
        <Link
          v-if="canRegister"
          href="/register"
          class="inline-block rounded-full px-8 py-3 border border-blue-600 text-blue-400 font-semibold hover:bg-blue-700 hover:text-white transition"
        >
          Registrar-se
        </Link>
      </div>
    </div>

    <!-- Rodapé -->
    <div class="relative z-10 mt-12 text-center text-xs text-gray-500">
      &copy; {{ new Date().getFullYear() }} Meu Kanban - Todos os direitos reservados.
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});
</script>
