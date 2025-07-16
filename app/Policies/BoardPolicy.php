<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization;

    /**
     * Antes de tudo: se for admin master, libera tudo.
     */
    public function before(User $user, $ability)
    {
        // Exemplo de acesso global (opcional)
        // if ($user->is_admin) {
        //     return true;
        // }
    }

    /**
     * Ver se o usuário pode ver a lista de boards.
     */
    public function viewAny(User $user)
    {
        return true; // Qualquer usuário autenticado pode ver seus boards
    }

    /**
     * Ver se o usuário pode ver um board específico.
     */
    public function view(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }

    /**
     * Ver se o usuário pode criar boards.
     */
    public function create(User $user)
    {
        return true; // Qualquer usuário logado pode criar boards
    }

    /**
     * Ver se o usuário pode atualizar um board.
     */
    public function update(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }

    /**
     * Ver se o usuário pode deletar um board.
     */
    public function delete(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }

    /**
     * Restaurar um board deletado.
     */
    public function restore(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }

    /**
     * Forçar deletar permanentemente.
     */
    public function forceDelete(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }
}
