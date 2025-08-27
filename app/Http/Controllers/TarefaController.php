<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // CORREÇÃO 1: A query agora começa já a filtrar pelo utilizador logado.
        $query = Tarefa::where('user_id', auth()->id());

        // Filtro de estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro de prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->input('prioridade'));
        }

        // CORREÇÃO 2: Adicionado o filtro de data que faltava
        if ($request->filled('data_vencimento')) {
            $query->whereDate('data_vencimento', $request->input('data_vencimento'));
        }

        $tarefas = $query->latest()->get();

        return Inertia::render('Tarefas/Index', [
            'tarefas' => $tarefas,
           
            'filtros' => $request->only(['estado', 'prioridade', 'data_vencimento']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prioridade' => 'nullable|string|in:baixa,media,alta',
            'data_vencimento' => 'nullable|date',
            'horario' => 'nullable|date_format:H:i',
        ]);

        // O seu código aqui estava correto, mas esta é uma forma ligeiramente mais limpa
        // de adicionar o user_id e criar a tarefa.
        $request->user()->tarefas()->create($validated);

        return Redirect::route('tarefas.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa): RedirectResponse
    {
        // CORREÇÃO 3: Adicionar verificação de autorização (boa prática).
        // Isto garante que um utilizador não pode editar a tarefa de outro.
        if ($tarefa->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prioridade' => 'nullable|string|in:baixa,media,alta',
            'data_vencimento' => 'nullable|date',
            'horario' => 'nullable|date_format:H:i',
        ]);

        $tarefa->update($validated);

        return Redirect::route('tarefas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa): RedirectResponse
    {
        // Adicionar verificação de autorização
        if ($tarefa->user_id !== auth()->id()) {
            abort(403);
        }

        $tarefa->delete();

        return Redirect::route('tarefas.index');
    }

    /**
     * Altera o estado de uma tarefa entre 'pendente' e 'concluida'.
     */
    public function toggle(Tarefa $tarefa): RedirectResponse
    {
        // Adicionar verificação de autorização
        if ($tarefa->user_id !== auth()->id()) {
            abort(403);
        }

        $tarefa->estado = $tarefa->estado === 'pendente' ? 'concluida' : 'pendente';

        $tarefa->save();

        return Redirect::route('tarefas.index');
    }
}
