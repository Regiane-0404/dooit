<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // CONTAGEM DAS TAREFAS
            'task_counts' => function () use ($request) {
                if ($user = $request->user()) {
                    return [
                        'pendente'  => $user->tarefas()->where('estado', 'pendente')->count(),
                        'concluida' => $user->tarefas()->where('estado', 'concluida')->count(),
                    ];
                }
                return [
                    'pendente'  => 0,
                    'concluida' => 0,
                ];
            },

            // INFORMAÇÕES DO UTILIZADOR AUTENTICADO
            'auth' => [
                'user' => $request->user()
                    ? [
                        'id'    => $request->user()->id,
                        'name'  => $request->user()->name,
                        'email' => $request->user()->email,
                    ]
                    : null,
            ],
        ]);
    }
}
