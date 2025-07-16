<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        // Permissões padrão para API tokens
        Jetstream::defaultApiTokenPermissions(['read']);

        // Admin: pode fazer tudo
        Jetstream::role('admin', 'Administrador', [
            'create',
            'read',
            'update',
            'delete',
            'invite',
            'manage_team',
            'manage_roles',
        ])->description('O Administrador pode realizar qualquer ação no sistema.');

        // Manager: gerencia equipe mas não tudo
        Jetstream::role('manager', 'Gerente', [
            'create',
            'read',
            'update',
            'invite',
            'manage_team',
        ])->description('O Gerente pode gerenciar recursos e membros da equipe.');

        // Editor: pode criar e editar conteúdo
        Jetstream::role('editor', 'Editor', [
            'create',
            'read',
            'update',
        ])->description('O Editor pode criar, ler e atualizar conteúdos.');

        // Viewer: só pode visualizar
        Jetstream::role('viewer', 'Visualizador', [
            'read',
        ])->description('O Visualizador pode apenas visualizar as informações.');
    }
}
