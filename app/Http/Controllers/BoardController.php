<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BoardController extends Controller
{
    /**
     * Protege todos os métodos usando a Policy do Board.
     */
    /** public function __construct()
     *{
     *   $this->authorizeResource(Board::class, 'board');
     *}
     */

    /**
     * Lista todos os boards do usuário autenticado.
     */
    public function index()
    {
        $boards = Board::where('user_id', auth()->id())
            ->with('tenant')
            ->latest()
            ->get();

        return Inertia::render('Boards/Index', [
            'boards' => $boards,
        ]);
    }

    public function show(Board $board)
    {
        $board->load([
            'tenant',
            'columns' => function ($query) {
                $query->orderBy('position');
            },
            'columns.cards' => function ($query) {
                $query->orderBy('position');
            },
        ]);

        return Inertia::render('Boards/Show', [
            'board' => $board,
        ]);
    }


    /**
     * Exibe o formulário para criar um novo board.
     */
    public function create()
    {
        $user = auth()->user();
        $tenants = $user->tenants()->get();

        return Inertia::render('Boards/Create', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Salva um novo board no banco.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'color' => ['nullable', 'string', 'max:7'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'background_image' => ['nullable', 'string'],
            'is_favorite' => ['sometimes', 'boolean'],
            'is_public' => ['sometimes', 'boolean'],
            'is_archived' => ['sometimes', 'boolean'],
            'is_template' => ['sometimes', 'boolean'],
            'is_read_only' => ['sometimes', 'boolean'],
            'is_collaborative' => ['sometimes', 'boolean'],
            'is_private' => ['sometimes', 'boolean'],
            'is_locked' => ['sometimes', 'boolean'],
            'is_pinned' => ['sometimes', 'boolean'],
            'is_hidden' => ['sometimes', 'boolean'],
            'is_archived_by_user' => ['sometimes', 'boolean'],
            'is_shared' => ['sometimes', 'boolean'],
            'is_synced' => ['sometimes', 'boolean'],
            'is_trashed' => ['sometimes', 'boolean'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = \Str::slug($validated['name']) . '-' . uniqid();

        Board::create($validated);


        return redirect()->route('boards.index')->with('success', 'Quadro criado com sucesso!');
    }

    /**
     * Exibe o formulário para editar um board.
     */
    public function edit(Board $board)
    {
        return Inertia::render('Boards/Edit', [
            'board' => $board,
        ]);
    }

    /**
     * Atualiza o board no banco.
     */
    public function update(Request $request, Board $board)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'background_image' => ['nullable', 'string'],
            'is_favorite' => ['sometimes', 'boolean'],
            'is_public' => ['sometimes', 'boolean'],
            'is_archived' => ['sometimes', 'boolean'],
            'is_template' => ['sometimes', 'boolean'],
            'is_read_only' => ['sometimes', 'boolean'],
            'is_collaborative' => ['sometimes', 'boolean'],
            'is_private' => ['sometimes', 'boolean'],
            'is_locked' => ['sometimes', 'boolean'],
            'is_pinned' => ['sometimes', 'boolean'],
            'is_hidden' => ['sometimes', 'boolean'],
            'is_archived_by_user' => ['sometimes', 'boolean'],
            'is_shared' => ['sometimes', 'boolean'],
            'is_synced' => ['sometimes', 'boolean'],
            'is_trashed' => ['sometimes', 'boolean'],
        ]);

        $board->update($validated);

        return redirect()->route('boards.index')->with('success', 'Quadro atualizado com sucesso!');
    }


    /**
     * Deleta (soft delete) o board.
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('boards.index')
            ->with('success', 'Quadro excluído com sucesso!');
    }
}
