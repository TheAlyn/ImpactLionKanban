<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="px-6 py-4 bg-gray-900 rounded-t-lg">
            <div class="text-lg font-medium text-gray-200">
                <slot name="title" />
            </div>

            <div class="mt-4 text-sm text-gray-300">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-800 text-end rounded-b-lg">
            <slot name="footer" />
        </div>
    </Modal>
</template>
