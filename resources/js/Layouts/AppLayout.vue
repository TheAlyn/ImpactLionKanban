<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-900 text-white">
            <nav class="bg-gray-900 border-b border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                <!-- Exemplo usando logo.png na pasta public -->
                                <img src="/logo.png" alt="Logo" class="block h-10 w-auto" />
                                <!-- Se preferir usar ApplicationMark.vue, deixe como está -->
                                <!-- <ApplicationMark class="block h-9 w-auto" /> -->
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <!-- Dashboard -->
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                                    class="text-gray-300 hover:text-white"
                                    active-class="text-white border-b-2 border-blue-500">
                                    Dashboard
                                </NavLink>

                                <!-- Minhas Empresas -->
                               <!--  <NavLink :href="route('companies.index')" :active="route().current('companies.index')"
                                    class="text-gray-300 hover:text-white"
                                    active-class="text-white border-b-2 border-blue-500">
                                    Minhas Empresas
                                </NavLink> -->

                                <!-- Gerenciar Empresas (apenas admin) -->
                               <!--  <NavLink v-if="$page.props.auth.user.is_admin" :href="route('companies.admin')"
                                    :active="route().current('companies.admin')" class="text-gray-300 hover:text-white"
                                    active-class="text-white border-b-2 border-blue-500">
                                    Gerenciar Empresas
                                </NavLink> -->
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                            <!-- Teams Dropdown -->
                            <div v-if="$page.props.jetstream.hasTeamFeatures" class="relative">
                                <Dropdown align="right" width="60">
                                    <template #trigger>
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                            {{ $page.props.auth.user.current_team.name }}
                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="w-60 bg-gray-800 text-gray-300 rounded-md shadow-lg py-2">
                                            <div class="px-4 py-2 text-xs uppercase tracking-wide text-gray-400">
                                                Gerenciar Equipe
                                            </div>

                                            <DropdownLink
                                                :href="route('teams.show', $page.props.auth.user.current_team)"
                                                class="hover:bg-gray-700">
                                                Configurações da equipe
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                                :href="route('teams.create')" class="hover:bg-gray-700">
                                                Criar nova equipe
                                            </DropdownLink>

                                            <div v-if="$page.props.auth.user.all_teams.length > 1"
                                                class="border-t border-gray-700 my-2"></div>

                                            <div v-if="$page.props.auth.user.all_teams.length > 1"
                                                class="px-4 py-2 text-xs uppercase tracking-wide text-gray-400">
                                                Trocar de equipe
                                            </div>

                                            <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                <form @submit.prevent="switchToTeam(team)">
                                                    <DropdownLink as="button" type="submit"
                                                        class="hover:bg-gray-700 flex items-center space-x-2">
                                                        <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                            class="h-5 w-5 text-green-400"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ team.name }}</span>
                                                    </DropdownLink>
                                                </form>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                :src="$page.props.auth.user.profile_photo_url"
                                                :alt="$page.props.auth.user.name" />
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-48 bg-gray-800 text-gray-300 rounded-md shadow-lg py-2">
                                            <div class="block px-4 py-2 text-xs text-gray-400">Gerenciar conta</div>

                                            <DropdownLink :href="route('profile.show')" class="hover:bg-gray-700">
                                                Perfil
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                                :href="route('api-tokens.index')" class="hover:bg-gray-700">
                                                API Tokens
                                            </DropdownLink>

                                            <div class="border-t border-gray-700 my-2"></div>

                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button" type="submit" class="hover:bg-gray-700">
                                                    Log Out
                                                </DropdownLink>
                                            </form>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out"
                                @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden bg-gray-900 border-t border-gray-700">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="text-gray-300 hover:text-white" active-class="text-white bg-gray-800">
                            Dashboard
                        </ResponsiveNavLink>

                        <ResponsiveNavLink :href="route('companies.index')" :active="route().current('companies.index')"
                            class="text-gray-300 hover:text-white" active-class="text-white bg-gray-800">
                            Minhas Empresas
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="$page.props.auth.user.is_admin" :href="route('companies.admin')"
                            :active="route().current('companies.admin')" class="text-gray-300 hover:text-white"
                            active-class="text-white bg-gray-800">
                            Gerenciar Empresas
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-700">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                            </div>
                            <div>
                                <div class="font-medium text-base text-gray-300">{{ $page.props.auth.user.name }}</div>
                                <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')"
                                class="text-gray-300 hover:text-white" active-class="text-white bg-gray-800">
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')" :active="route().current('api-tokens.index')"
                                class="text-gray-300 hover:text-white" active-class="text-white bg-gray-800">
                                API Tokens
                            </ResponsiveNavLink>

                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button" type="submit"
                                    class="text-gray-300 hover:text-white w-full text-left">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>