<!-- resources/js/Pages/Boards/Edit.vue -->
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { EditorContent, useEditor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Underline from '@tiptap/extension-underline'
import TextAlign from '@tiptap/extension-text-align'
import { ref, computed } from 'vue'
import DialogModal from '@/Components/DialogModal.vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'

// Carrega todos os ícones “solid”
library.add(fas)

// Props
const props = defineProps({
    board: Object,
    errors: Object,
})

// Formulário Inertia
const form = useForm({
    _method: 'PUT',
    name: props.board.name || '',
    slug: props.board.slug || '',
    icon: props.board.icon || 'fas fa-book',
    background_image: null,
    color: props.board.color || '#ffffff',
    description: props.board.description || '',
    is_favorite: !!props.board.is_favorite,
    is_public: !!props.board.is_public,
    is_archived: !!props.board.is_archived,
    is_template: !!props.board.is_template,
    is_read_only: !!props.board.is_read_only,
    is_collaborative: !!props.board.is_collaborative,
    is_private: !!props.board.is_private,
    is_locked: !!props.board.is_locked,
    is_pinned: !!props.board.is_pinned,
    is_hidden: !!props.board.is_hidden,
    is_archived_by_user: !!props.board.is_archived_by_user,
    is_shared: !!props.board.is_shared,
    is_synced: !!props.board.is_synced,
    is_trashed: !!props.board.is_trashed,
})

// State do modal de ícones
const showIconPicker = ref(false)
const availableIcons = Object.values(fas)
    .map(i => ['fas', i.iconName])
    .sort((a, b) => a[1].localeCompare(b[1]))

// TipTap editor
const editor = useEditor({
    extensions: [
        StarterKit,
        Underline,
        Placeholder.configure({ placeholder: 'Descrição detalhada do quadro…' }),
        TextAlign.configure({ types: ['heading', 'paragraph'] }),
    ],
    content: form.description,
    onUpdate({ editor }) {
        form.description = editor.getHTML()
    }
})

// Preview da capa
const previewUrl = computed(() => {
    if (form.background_image) {
        return window.URL.createObjectURL(form.background_image)
    }
    return props.board.background_image
        ? `/storage/${props.board.background_image}`
        : 'https://via.placeholder.com/600x160?text=Sem+Capa'
})

// Envio do form
function submit() {
    // apenas para depuração
    //console.log('>>> Request payload (form.data()):', form.data())
    form.post(route('boards.update', props.board.id), {
        preserveState: true,
        onError: errors => console.error('Erros de validação:', errors),
        onSuccess: () => console.log('Atualizado com sucesso!'),
    })
}

// Upload de imagem
function onFileChange(e) {
    form.background_image = e.target.files[0]
}

// Config flags
const flagConfig = [
    ['is_favorite', 'Favorito', 'Menu de favoritos'],
    ['is_archived', 'Arquivado', 'Menu de arquivados'],
    ['is_private', 'Privado', 'Só você vê'],
    ['is_pinned', 'Fixado', 'Fixa no dashboard'],
    ['is_archived_by_user', 'Arquivado por você', 'Só você vê'],
    ['is_synced', 'Sincronizado', 'Sincroniza com outro quadro'],
    ['is_public', 'Público', 'Visível para toda equipe'],
    ['is_template', 'Template', 'Usado como modelo'],
    ['is_collaborative', 'Colaborativo', 'Aceita colaboradores'],
    ['is_locked', 'Bloqueado', 'Sem alterações possíveis'],
    ['is_hidden', 'Oculto', 'Não aparece na listagem'],
    ['is_shared', 'Compartilhado', 'Compartilhado com outros'],
    ['is_trashed', 'Na lixeira', 'Está na lixeira'],
]
</script>

<template>
    <AppLayout>

        <Head title="Editar Quadro" />

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
            <!-- Cabeçalho -->
            <header class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold flex items-center space-x-3" :style="{ color: form.color }">
                    <FontAwesomeIcon :icon="form.icon.split(' ')" class="text-2xl" />
                    <span>Editar Quadro</span>
                </h1>
                <Link :href="route('boards.index')"
                    class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600 transition">← Meus Quadros</Link>
            </header>

            <!-- Contagens -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <Link :href="route('boards.columns.index', props.board.id)"
                    class="bg-gray-700 p-4 rounded flex items-center space-x-2 hover:bg-gray-600">
                <FontAwesomeIcon :icon="['fas', 'list']" />
                <span>{{ props.board.columns_count }} listas</span>
                </Link>
                <Link :href="route('boards.cards.index', props.board.id)"
                    class="bg-gray-700 p-4 rounded flex items-center space-x-2 hover:bg-gray-600">
                <FontAwesomeIcon :icon="['fas', 'clone']" />
                <span>{{ props.board.cards_count }} cartões</span>
                </Link>
            </div>

            <!-- Formulário -->
            <form @submit.prevent="submit" class="bg-gray-800 p-6 rounded space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label class="block text-sm text-gray-200 mb-1">Nome</label>
                        <input name="name" v-model="form.name" type="text"
                            class="w-full px-3 py-2 bg-gray-900 rounded border border-gray-700 focus:ring-blue-500" />
                        <div v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</div>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm text-gray-200 mb-1">Slug (URL)</label>
                        <input name="slug" v-model="form.slug" type="text" readonly
                            class="w-full px-3 py-2 bg-gray-900 rounded border border-gray-700 focus:ring-blue-500" />
                        <div v-if="errors.slug" class="text-red-500 text-xs mt-1">{{ errors.slug }}</div>
                    </div>

                    <!-- Imagem de Fundo -->
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-200 mb-1">Imagem de Fundo</label>
                        <input name="background_image" type="file" accept="image/*" @change="onFileChange"
                            class="block bg-gray-900 rounded border border-gray-700 p-2" />
                        <div v-if="errors.background_image" class="text-red-500 text-xs mt-1">
                            {{ errors.background_image }}
                        </div>
                        <img :src="previewUrl" alt="Preview da capa" class="mt-2 w-full h-48 object-cover rounded" />
                    </div>

                    <!-- Color picker -->
                    <div>
                        <label class="block text-sm text-gray-200 mb-1">Cor do Quadro</label>
                        <input name="color" v-model="form.color" type="color" class="w-full h-12" />
                        <div v-if="errors.color" class="text-red-500 text-xs mt-1">{{ errors.color }}</div>
                    </div>

                    <!-- Ícone -->
                    <div>
                        <label class="block text-sm text-gray-200 mb-1">Ícone</label>
                        <input name="icon" type="hidden" v-model="form.icon" />
                        <button type="button" @click="showIconPicker = true"
                            class="flex items-center px-3 py-2 bg-gray-700 rounded hover:bg-gray-600">
                            <FontAwesomeIcon :icon="form.icon.split(' ')" />
                            <span class="ml-2">Alterar ícone</span>
                        </button>
                        <div v-if="errors.icon" class="text-red-500 text-xs mt-1">{{ errors.icon }}</div>
                    </div>

                    <!-- Descrição Rich Text -->
                    <div class="md:col-span-2 flex flex-col">
                        <label class="block text-sm text-gray-200 mb-1">Descrição</label>
                        <!-- Toolbar completa -->
                        <div class="flex mb-2 space-x-2">
                            <button type="button" @click="editor?.chain().focus().toggleBold().run()"
                                :class="{ 'text-blue-400': editor?.isActive('bold') }"><i
                                    class="fas fa-bold"></i></button>
                            <button type="button" @click="editor?.chain().focus().toggleItalic().run()"
                                :class="{ 'text-blue-400': editor?.isActive('italic') }"><i
                                    class="fas fa-italic"></i></button>
                            <button type="button" @click="editor?.chain().focus().toggleUnderline().run()"
                                :class="{ 'text-blue-400': editor?.isActive('underline') }"><i
                                    class="fas fa-underline"></i></button>
                            <button type="button" @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
                                :class="{ 'text-blue-400': editor?.isActive('heading', { level: 2 }) }">H2</button>
                            <button type="button" @click="editor?.chain().focus().toggleBulletList().run()"
                                :class="{ 'text-blue-400': editor?.isActive('bulletList') }"><i
                                    class="fas fa-list-ul"></i></button>
                            <button type="button" @click="editor?.chain().focus().toggleOrderedList().run()"
                                :class="{ 'text-blue-400': editor?.isActive('orderedList') }"><i
                                    class="fas fa-list-ol"></i></button>
                        </div>
                        <!-- Editor com resize vertical -->
                        <div
                            class="bg-gray-900 border border-gray-700 rounded resize-y overflow-auto p-2 flex-1 min-h-[120px]">
                            <EditorContent :editor="editor" />
                        </div>
                        <div v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description }}</div>
                    </div>
                </div>

                <!-- Flags -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template v-for="([key, label, tip]) in flagConfig" :key="key">
                        <label class="inline-flex items-center text-sm text-gray-200">
                            <input type="checkbox" v-model="form[key]" class="form-checkbox" />
                            <span class="ml-2" :title="tip">{{ label }}</span>
                        </label>
                    </template>
                </div>

                <!-- Ações -->
                <div class="flex justify-end space-x-4">
                    <Link :href="route('boards.show', props.board.id)"
                        class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600">Cancelar
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>

            <!-- Modal de ícones -->
            <DialogModal :show="showIconPicker" @close="showIconPicker = false">
                <template #title>Escolha um ícone</template>
                <template #content>
                    <div class="grid grid-cols-5 gap-3 p-4 max-h-80 overflow-auto">
                        <button v-for="icon in availableIcons" :key="icon[1]"
                            @click="form.icon = `fas ${icon[1]}`; showIconPicker = false"
                            class="p-2 bg-gray-700 rounded hover:bg-gray-600 flex items-center justify-center">
                            <FontAwesomeIcon :icon="icon" class="text-xl text-white" />
                        </button>
                    </div>
                </template>
            </DialogModal>
        </div>
    </AppLayout>
</template>

<style scoped>
.resize-y {
    resize: vertical;
}
</style>
