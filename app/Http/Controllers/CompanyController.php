<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    // Listar as empresas que o usuário pertence
    public function index()
    {
        $tenants = auth()->user()->tenants()->get();

        return Inertia::render('Companies/Index', [
            'companies' => $tenants,
        ]);
    }

    // Tela de administração para o admin ver todas as empresas
    public function admin()
    {
        $user = auth()->user();

        if (! $user->is_admin) {
            abort(403, 'Unauthorized.');
        }

        $companies = Tenant::with('owner')->get();

        return Inertia::render('Companies/Admin', [
            'companies' => $companies,
        ]);
    }

    // Mostrar o formulário para criar uma empresa (tenant)
    public function create()
    {
        $this->authorize('admin'); // Gate para garantir admin

        return Inertia::render('Companies/Create');
    }

    // Salvar empresa nova
    public function store(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'description' => 'nullable|string',
            'domain' => 'nullable|string|unique:tenants,domain',
            // demais campos que quiser validar
        ]);

        $tenant = Tenant::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'domain' => $validated['domain'] ?? null,
            'owner_id' => auth()->id(),
            'is_active' => true,
        ]);

        // Pode adicionar relacionamento com o dono e outras coisas aqui

        return redirect()->route('companies.admin')->with('success', 'Empresa criada com sucesso!');
    }

    // Aqui podemos adicionar update, edit, delete futuramente
}
