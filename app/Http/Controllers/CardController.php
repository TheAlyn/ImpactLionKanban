<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Armazena um novo cartão.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'column_id'   => 'required|exists:columns,id',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:255',
        ]);

        // Puxa dados de board e tenant a partir da coluna
        $column = Column::findOrFail($validated['column_id']);

        $card = Card::create([
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'column_id'        => $column->id,
            'board_id'         => $column->board_id,
            'tenant_id'        => $column->tenant_id,
            'user_id'          => auth()->id(),
            'priority'         => $validated['priority'],
            'due_date'         => $validated['due_date'] ?? null,
            'color'            => $validated['color'] ?? '#FFFFFF',
            'icon'             => $validated['icon'] ?? null,
            'slug'             => Str::slug($validated['title']) . '-' . uniqid(),
        ]);

        return back();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Atualiza um cartão existente.
     * Pode ser usado tanto para editar título/descrição quanto para arrastar entre colunas
     * (ajustando column_id e position).
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'column_id'   => 'sometimes|required|exists:columns,id',
            'priority'    => 'sometimes|required|in:low,medium,high',
            'due_date'    => 'sometimes|nullable|date',
            'color'       => 'sometimes|nullable|string|max:7',
            'icon'        => 'sometimes|nullable|string|max:255',
            'position'    => 'sometimes|required|integer',
            'is_archived' => 'sometimes|boolean',
        ]);

        $card->update($validated);

        return back();
    }

    /**
     * Move o cartão para outra coluna e posição.
     */
    public function move(Request $request)
    {
        $data = $request->validate([
            'card_id'   => 'required|exists:cards,id',
            'column_id' => 'required|exists:columns,id',
            'position'  => 'required|integer',
        ]);

        $card = Card::findOrFail($data['card_id']);
        $card->update([
            'column_id' => $data['column_id'],
            'position'  => $data['position'],
        ]);

        return response()->json(['success' => true]);
    }



    /**
     * Remove (soft delete) um cartão.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return back();
    }
}
