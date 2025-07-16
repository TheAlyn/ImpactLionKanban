<template>
  <div>
    <h1>Gerenciar Empresas</h1>
    <router-link :href="route('companies.create')" class="btn btn-primary mb-4">Criar nova empresa</router-link>

    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Dono</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="company in companies" :key="company.id">
          <td>{{ company.name }}</td>
          <td>{{ company.owner?.name ?? 'Sem dono' }}</td>
          <td>{{ company.is_active ? 'Ativa' : 'Inativa' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  companies: Array,
});
</script>
