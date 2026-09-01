<?php namespace App\Libraries;

use Config\Services;

class MoodleClient
{
    private string $base;
    private string $token;
    private $http;

    public function __construct(?string $base = null, ?string $token = null)
    {
        $this->base  = rtrim($base ?? env('MOODLE_BASE'), '/');
        $this->token = $token ?? env('MOODLE_TOKEN');
        $this->http  = Services::curlrequest([
            'timeout' => 10,
            'http_errors' => false,
        ]);
    }

    /**
     * Call a Moodle webservice function via REST (x-www-form-urlencoded).
     * @param string $function
     * @param array  $params   (flattened into form fields)
     */
    public function call(string $function, array $params = []): array
    {
        $url = "{$this->base}/webservice/rest/server.php";
        $form = array_merge($params, [
            'wstoken'             => $this->token,
            'wsfunction'          => $function,
            'moodlewsrestformat'  => 'json',
        ]);

        $response = $this->http->post($url, ['form_params' => $form]);
        $json = json_decode($response->getBody(), true);

        if (isset($json['exception'])) {
            throw new \RuntimeException("Moodle error: {$json['message']} ({$json['exception']})");
        }
        return $json ?? [];
    }

    /**
     * Try to resolve Moodle user id from email or phone.
     * Prefers email (exact), falls back to phone1/phone2 if available as criteria.
     */
    public function resolveUserId(?string $email, ?string $phone): ?int
    {
        // 1) by email
        if ($email) {
            $res = $this->call('core_user_get_users', [
                'criteria[0][key]'   => 'email',
                'criteria[0][value]' => $email,
            ]);
            if (!empty($res['users'][0]['id'])) {
                return (int) $res['users'][0]['id'];
            }
        }

        // 2) by phone fields (if stored in Moodle profile)
        foreach (['phone1', 'phone2'] as $field) {
            if ($phone) {
                $res = $this->call('core_user_get_users', [
                    'criteria[0][key]'   => $field,
                    'criteria[0][value]' => $phone,
                ]);
                if (!empty($res['users'][0]['id'])) {
                    return (int) $res['users'][0]['id'];
                }
            }
        }

        return null;
    }

    /**
     * Create a Note for a user with recording/transcript links if present.
     */
    public function createUserNote(int $userId, string $text, string $state = null, int $format = 1): array
    {
        $state = $state ?? (env('MOODLE_NOTE_PUBLISHSTATE') ?: 'site');

        // core_notes_create_notes expects a notes[] array, urlencoded
        $params = [
            'notes[0][userid]'       => (string) $userId,
            'notes[0][publishstate]' => $state,    // site | course | personal
            'notes[0][text]'         => $text,
            'notes[0][format]'       => (string) $format, // 1=HTML, 0=MOODLE
        ];
        return $this->call('core_notes_create_notes', $params);
    }
}
