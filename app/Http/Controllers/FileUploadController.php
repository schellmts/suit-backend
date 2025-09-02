<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function store(Network $network, Account $account, Request $request, Ticket $ticket)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'move_id' => 'required|integer|exists:ticket_movement,id',
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->getRealPath();
            $fileMimeType = $file->getMimeType();

            $accessToken = $this->getAccessToken();

            $folderName = env('MS_ONEDRIVE_FOLDER_NAME');
            $userId = env('MS_GRAPH_USER_ID');

            $uploadUrl = 'https://graph.microsoft.com/v1.0/users/' . $userId . '/drive/root:/' . $folderName . '/' . $fileName . ':/content';

            $fileContent = file_get_contents($filePath);

            $guzzle = new GuzzleClient();
            $response = $guzzle->put($uploadUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => $fileMimeType,
                ],
                'body' => $fileContent,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            $driveFileId = $responseBody['id'] ?? null;

            if (empty($driveFileId)) {
                throw new Exception('Falha ao obter o ID do arquivo no OneDrive. Resposta recebida: ' . json_encode($responseBody));
            }

            TicketAttachment::create([
                'account_id' => $ticket->account_id,
                'ticket_id' => $ticket->id,
                'move_id' => $request->input('move_id'),
                'filename' => $file->getClientOriginalName(),
                'url_target' => $driveFileId,
            ]);

            return response()->json([
                'message' => 'Anexo enviado para o OneDrive com sucesso!',
                'onedrive_file_id' => $driveFileId
            ], 201);

        } catch (Exception $e) {
            Log::error('Erro no upload para o OneDrive: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocorreu um erro inesperado durante o upload.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getAccessToken(): string
    {
        $guzzle = new GuzzleClient();
        $url = 'https://login.microsoftonline.com/' . env('MS_GRAPH_TENANT_ID') . '/oauth2/v2.0/token';

        try {
            $response = $guzzle->post($url, [
                'form_params' => [
                    'client_id' => env('MS_GRAPH_CLIENT_ID'),
                    'client_secret' => env('MS_GRAPH_CLIENT_SECRET'),
                    'scope' => 'https://graph.microsoft.com/.default',
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $body = json_decode($response->getBody()->getContents());
            return $body->access_token;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            Log::error('Erro ao obter token da Microsoft: ' . $responseBody);
            throw new Exception('Não foi possível autenticar com a Microsoft. Verifique as credenciais no .env e as permissões no Azure.');
        }
    }
}
