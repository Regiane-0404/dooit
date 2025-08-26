<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\RedirectResponse; // Importante para o tipo de retorno
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect; // Importante para o redirecionamento
use Inertia\Inertia;
use Inertia\Response;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Tarefa::query();

        // Filtro de estado (já existente)
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // NOVO: Filtro de prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->input('prioridade'));
        }

        $tarefas = $query->latest()->get();

        return Inertia::render('Tarefas/Index', [
            'tarefas' => $tarefas,
            // Agora passamos ambos os filtros para o frontend
            'filtros' => $request->only(['estado', 'prioridade']),
        ]);
    }

    public function create()
    {
        // Não vamos usar este método diretamente com o Vue.js, o formulário estará num modal.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validar os dados recebidos do formulário.
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prioridade' => 'nullable|string|in:baixa,media,alta',
            'data_vencimento' => 'nullable|date',
            'horario' => 'nullable|date_format:H:i',
        ]);

        // 2. Criar a tarefa na base de dados usando o Model.
        // O Laravel vai preencher automaticamente as colunas com base no $fillable que definimos.
        Tarefa::create($validated);

        // 3. Redirecionar o utilizador de volta para a página de listagem de tarefas.
        return Redirect::route('tarefas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarefa $tarefa)
    {
        //
    }

    public function update(Request $request, Tarefa $tarefa): RedirectResponse
    {
        // 1. Validar os dados recebidos (as mesmas regras da criação).
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prioridade' => 'nullable|string|in:baixa,media,alta',
            'data_vencimento' => 'nullable|date',
            'horario' => 'nullable|date_format:H:i',
        ]);

        // 2. Atualizar a tarefa com os dados validados.
        $tarefa->update($validated);

        // 3. Redirecionar de volta para a página de listagem.
        return Redirect::route('tarefas.index');
    }

    public function destroy(Tarefa $tarefa): RedirectResponse
    {
        // Usa o método 'delete' do Eloquent para remover o registo da base de dados.
        $tarefa->delete();

        // Redireciona de volta para a página de listagem.
        return Redirect::route('tarefas.index');
    }
    /**
     * Altera o estado de uma tarefa entre 'pendente' e 'concluida'.
     */
    public function toggle(Tarefa $tarefa): RedirectResponse
    {
        // Altera o estado da tarefa
        $tarefa->estado = $tarefa->estado === 'pendente' ? 'concluida' : 'pendente';

        // Guarda a alteração na base de dados
        $tarefa->save();

        // Redireciona de volta para a página de listagem
        return Redirect::route('tarefas.index');
    }
}
