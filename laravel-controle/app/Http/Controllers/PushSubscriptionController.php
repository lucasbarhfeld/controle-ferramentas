<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'endpoint' => ['required', 'url', 'max:2048'],
            'keys' => ['required', 'array'],
            'keys.p256dh' => ['required', 'string', 'max:512'],
            'keys.auth' => ['required', 'string', 'max:255'],
            'contentEncoding' => ['nullable', 'string', 'in:aesgcm,aes128gcm'],
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint_hash' => hash('sha256', $dados['endpoint'])],
            [
                'user_id' => $request->user()->id,
                'endpoint' => $dados['endpoint'],
                'public_key' => $dados['keys']['p256dh'],
                'auth_token' => $dados['keys']['auth'],
                'content_encoding' => $dados['contentEncoding'] ?? 'aes128gcm',
            ],
        );

        return response()->json(['message' => 'Notificações ativadas neste celular.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'endpoint' => ['required', 'url', 'max:2048'],
        ]);

        PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint_hash', hash('sha256', $dados['endpoint']))
            ->delete();

        return response()->json(['message' => 'Notificações desativadas neste celular.']);
    }
}
