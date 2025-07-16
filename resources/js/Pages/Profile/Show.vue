<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
  <AppLayout title="Perfil">
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">
        Meu Perfil
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-10">
      <div v-if="$page.props.jetstream.canUpdateProfileInformation">
        <UpdateProfileInformationForm :user="$page.props.auth.user" />

        <SectionBorder />
      </div>

      <div v-if="$page.props.jetstream.canUpdatePassword">
        <UpdatePasswordForm />

        <SectionBorder />
      </div>

      <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
        <TwoFactorAuthenticationForm
          :requires-confirmation="confirmsTwoFactorAuthentication"
        />

        <SectionBorder />
      </div>

      <LogoutOtherBrowserSessionsForm :sessions="sessions" />

      <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
        <SectionBorder />

        <DeleteUserForm />
      </template>
    </div>
  </AppLayout>
</template>
