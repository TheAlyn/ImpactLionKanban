<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColumnController extends Controller
{
    /**
     * Salva uma nova coluna.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
        ]);


        $column = new Column();
        $column->tenant_id = auth()->user()->tenant_id;
        $column->board_id = $request->board_id;
        $column->name = $request->name;
        $column->color = '#888';
        $column->position = Column::where('board_id', $request->board_id)->max('position') + 1;
        $column->slug = Str::slug($request->name) . '-' . uniqid();

        $column->save();

        return back()->with('success', 'Coluna criada com sucesso!');
    }

    /**
     * Atualiza uma coluna.
     */
    public function update(Request $request, Column $column)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $column->update($validated);

        return back()->with('success', 'Coluna atualizada com sucesso!');
    }

    /**
     * Deleta uma coluna.
     */
    public function destroy(Column $column)
    {
        $boardId = $column->board_id;

        $column->cards()->delete(); // Opcional: deleta os cards junto
        $column->delete();

        return redirect()->route('boards.show', $boardId)
            ->with('success', 'Coluna removida com sucesso!');
    }

    // (Opcional) Para drag & drop:
    // public function updateOrder(Request $request)
    // {
    //     foreach ($request->order as $index => $columnId) {
    //         Column::where('id', $columnId)->update(['order' => $index + 1]);
    //     }
    //     return response()->json(['status' => 'ok']);
    // }
}
