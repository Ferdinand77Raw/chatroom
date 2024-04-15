<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventStreamController extends Controller
{
    // Controlador para obtener mensajes nuevos
    public function getNewMessages(Request $request)
    {
        $lastCheckedAt = $request->input('last_checked_at');

        $newMessages = Message::where('created_at', '>', $lastCheckedAt)->get();

        return response()->json($newMessages);
    }

    public function stream(Request $request)
    {
        // Establecer cabeceras para SSE
        $response = new StreamedResponse(function () {
            while (true) {
                // Obtener los datos que deseas enviar
                $data = [
                    'message' => 'Hola desde el servidor',
                    'timestamp' => now()->toDateTimeString(),
                ];

                // Enviar el evento al cliente
                echo "data: " . json_encode($data) . "\n\n";

                // Flushear los datos para enviarlos inmediatamente al cliente
                ob_flush();
                flush();

                // Esperar un intervalo de tiempo antes de enviar el próximo evento
                sleep(1);
            }
        });

        // Establecer cabeceras para SSE
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

}
