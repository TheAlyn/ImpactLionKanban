<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
  <button 
    :type="type"
    class="inline-flex items-center px-4 py-2 
           bg-gray-700 border border-gray-600 rounded-md 
           font-semibold text-xs text-gray-200 uppercase tracking-widest shadow-sm
           hover:bg-gray-600 
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
           disabled:opacity-25 transition ease-in-out duration-150">
    <slot />
  </button>
</template>

