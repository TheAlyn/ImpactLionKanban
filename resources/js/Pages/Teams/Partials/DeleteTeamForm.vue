<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    team: Object,
});

const confirmingTeamDeletion = ref(false);
const form = useForm({});

const confirmTeamDeletion = () => {
    confirmingTeamDeletion.value = true;
};

const deleteTeam = () => {
    form.delete(route('teams.destroy', props.team), {
        errorBag: 'deleteTeam',
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            <span class="text-gray-100">Deletar equipe</span>
        </template>

        <template #description>
            Excluir esta equipe permanentemente.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Após a exclusão de uma equipe, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir esta equipe, baixe todos os dados ou informações sobre ela que você deseja manter.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmTeamDeletion">
                    Excluir equipe
                </DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                   Excluir equipe
                </template>

                <template #content>
                    Tem certeza de que deseja excluir esta equipe? Após a exclusão de uma equipe, todos os seus recursos e dados serão excluídos permanentemente.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingTeamDeletion = false">
                        Cancelar
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam"
                    >
                        Excluir equipe
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
