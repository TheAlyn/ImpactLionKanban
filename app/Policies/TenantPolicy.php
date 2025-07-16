<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    /**
     * Ver se o usuário pode ver a lista de empresas.
     */
    public function viewAny(User $user): bool
    {
        // Admin ou usuário que já faz parte de alguma empresa
        return $user->is_admin || $user->tenants()->exists();
    }

    /**
     * Ver se o usuário pode ver uma empresa específica.
     */
    public function view(User $user, Tenant $tenant): bool
    {
        // Admin, dono ou membro da empresa
        return $user->is_admin || $user->id === $tenant->owner_id || $tenant->users->contains($user);
    }

    /**
     * Ver se o usuário pode criar uma empresa.
     */
    public function create(User $user): bool
    {
        // Só admin pode criar empresas
        return $user->is_admin;
    }

    /**
     * Ver se o usuário pode atualizar uma empresa.
     */
    public function update(User $user, Tenant $tenant): bool
    {
        // Admin ou dono podem editar
        return $user->is_admin || $user->id === $tenant->owner_id;
    }

    /**
     * Ver se o usuário pode excluir uma empresa.
     */
    public function delete(User $user, Tenant $tenant): bool
    {
        // Admin ou dono podem excluir
        return $user->is_admin || $user->id === $tenant->owner_id;
    }

    /**
     * Não vamos liberar restore.
     */
    public function restore(User $user, Tenant $tenant): bool
    {
        return false;
    }

    /**
     * Não vamos liberar forceDelete.
     */
    public function forceDelete(User $user, Tenant $tenant): bool
    {
        return false;
    }
}
