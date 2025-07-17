<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            ->withCount([
                'columns',
                'cards',
            ])
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
        // Eager-load relacionamentos que precisamos
        $board->load([
            'columns',
            'cards'
        ]);

        // Carregando o count de colunas e de cards
        $board->loadCount(['columns', 'cards']);

        return Inertia::render('Boards/Edit', [
            'board' => $board,
        ]);
    }

    /**
     * Atualiza o board no banco.
     */
    public function update(Request $request, Board $board)
    {
       // loga TODA a request — inclusive arquivos (!)
       //Log::info('Board.update – incoming request:', $request->all()); 

        $rules = [
            'name'               => 'sometimes|required|string|max:255',
            'slug'               => 'sometimes|required|string|max:255|unique:boards,slug,' . $board->id,
            'icon'               => 'sometimes|nullable|string|max:255',
            'background_image'   => 'sometimes|nullable|image|max:2048',
            'color'              => 'sometimes|required|string|size:7',
            'description'        => 'sometimes|nullable|string',
            'is_favorite'        => 'sometimes|boolean',
            'is_public'          => 'sometimes|boolean',
            'is_archived'        => 'sometimes|boolean',
            'is_template'        => 'sometimes|boolean',
            'is_read_only'       => 'sometimes|boolean',
            'is_collaborative'   => 'sometimes|boolean',
            'is_private'         => 'sometimes|boolean',
            'is_locked'          => 'sometimes|boolean',
            'is_pinned'          => 'sometimes|boolean',
            'is_hidden'          => 'sometimes|boolean',
            'is_archived_by_user'=> 'sometimes|boolean',
            'is_shared'          => 'sometimes|boolean',
            'is_synced'          => 'sometimes|boolean',
            'is_trashed'         => 'sometimes|boolean',
        ];

        $data = $request->validate($rules);

        // 2) Logar os dados validados
        //Log::info('Board.update – validated data:', $data);

        // Flags booleanas: garante true/false
        foreach ([
            'is_favorite','is_public','is_archived','is_template','is_read_only',
            'is_collaborative','is_private','is_locked','is_pinned','is_hidden',
            'is_archived_by_user','is_shared','is_synced','is_trashed',
        ] as $flag) {
            if ($request->has($flag)) {
                $data[$flag] = (bool) $request->input($flag);
            }
        }

        // 3) Upload de imagem (se houver) com nome único
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            // opcional: deleta a anterior
            if ($board->background_image) {
                Storage::disk('public')->delete($board->background_image);
            }
            // gera nome único
            $filename = 'board_'.$board->id.'_'.Str::uuid().'.'.$file->extension();
            $path = $file->storeAs('boards/covers', $filename, 'public');
            $data['background_image'] = $path;

           //Log::info("Board.update – new cover stored at: {$path}");
        }

        // 4) Atualiza somente os campos alterados
        $board->update($data);

        //Log::info("Board.update – board {$board->id} updated successfully");

        return redirect()
            ->route('boards.show', $board)
            ->with('success', 'Quadro atualizado com sucesso!');
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
