<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center px-4 py-2
           bg-blue-600 border border-transparent rounded-md
           font-semibold text-xs text-white uppercase tracking-widest
           hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800
           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
           disabled:opacity-50 transition ease-in-out duration-150"
  >
    <slot />
  </button>
</template>

