<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Board;
use App\Models\Column;
use App\Models\Card;

class DashboardController extends Controller
{

    /**
     * Exibe o Dashboard com estatísticas, boards e atividades recentes.
     */
    public function index()
    {
        $user = auth()->user();

        // Boards do usuário (últimos 6)
        $boards = $user->boards()->with('tenant')->latest()->take(6)->get();

        // Estatísticas básicas
        $stats = [
            'boards' => $user->boards()->count(),
            'columns' => $user->columns()->count(),
            'cards' => $user->cards()->count(),
        ];

        // Buscar últimas atividades reais baseadas em criação (exemplo)
        $recentBoards = $user->boards()->latest()->take(3)->get(['id', 'name', 'created_at']);
        $recentCards = $user->cards()->latest()->take(3)->get(['id', 'title', 'created_at']);
        $recentColumns = $user->columns()
            ->latest()
            ->take(3)
            ->select('columns.id', 'columns.name', 'columns.created_at')
            ->get();

        // Montar array unificado de atividades
        $recentActivities = collect();

        foreach ($recentBoards as $board) {
            $recentActivities->push([
                'id' => 'board-' . $board->id,
                'description' => 'Você criou o board "' . $board->name . '"',
                'created_at' => $board->created_at,
            ]);
        }

        foreach ($recentCards as $card) {
            $recentActivities->push([
                'id' => 'card-' . $card->id,
                'description' => 'Você adicionou o card "' . $card->title . '"',
                'created_at' => $card->created_at,
            ]);
        }

        foreach ($recentColumns as $column) {
            $recentActivities->push([
                'id' => 'column-' . $column->id,
                'description' => 'Você criou a coluna "' . $column->name . '"',
                'created_at' => $column->created_at,
            ]);
        }

        // Ordenar todas as atividades pela data decrescente e pegar as últimas 6
        $recentActivities = $recentActivities
            ->sortByDesc('created_at')
            ->take(6)
            ->map(function ($item) {
                $item['created_at'] = \Carbon\Carbon::parse($item['created_at'])->diffForHumans();
                return $item;
            })
            ->values();

        return Inertia::render('Dashboard', [
            'boards' => $boards,
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }
}
