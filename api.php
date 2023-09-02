<?php

if (version_compare(phpversion(), "8.0.0", "<=")) {
    die('Use PHP 8 or later :) Stay Updated');
}

class hiddifyApi
{
    protected $urlUser, $urlAdmin;
    public $User;

    function __construct($mainUrl, $path, $adminSecret)
    {
        $this->urlUser = $mainUrl . '/' . $path . '/';
        $this->urlAdmin = $mainUrl . '/' . $path . '/' . $adminSecret . '/';

        $this->User = new User($mainUrl, $path, $adminSecret);
    }

    public function is_connected(): bool
    {
        $url = $this->urlAdmin . 'admin/get_data/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        $retVal = (is_array($response)) ? true : false;
        return $retVal;
    }

    public function getSystemStats(): array
    {
        $url = $this->urlAdmin . 'admin/get_data/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        return $response['stats'];
    }

    protected function generateRandomUUID(): string
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    /* 
    protected function getcrftoken(string $path): string
    {
        // Load the HTML content into a DOMDocument object
        $url = $this->urlAdmin . $path; // 

        $html = file_get_contents($url);
        $doc = new DOMDocument();
        $doc->loadHTML($html);

        // Create a DOMXPath object and use it to query the document for the csrf_token input field
        $xpath = new DOMXPath($doc);
        $input = $xpath->query('//input[@name="csrf_token"]')->item(0);

        // Get the value of the csrf_token input field
        $csrfToken = $input->getAttribute('value');

        // Output the value of the csrf_token input field
        return $csrfToken;
    }
    */
}

class User extends hiddifyApi
{
    protected $adminSecret;

    public function __construct($mainUrl, $path, $adminSecret)
    {
        $this->adminSecret = $adminSecret;
        $this->urlUser = $mainUrl . '/' . $path . '/';
        $this->urlAdmin = $mainUrl . '/' . $path . '/' . $adminSecret . '/';
    }

    public function getUserList(): array
    {
        $url = $this->urlAdmin . 'api/v1/user/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        return $data;
    }

    public function addUser(
        string $name,
        int $package_days = 30,
        int $package_size = 30,
        string $telegram_id = null,
        string $comment = null,
        string $resetMod = 'no_reset'
    ): string | bool {
        $url = $this->urlAdmin . 'api/v1/user/';

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );

        $uuid = $this->generateRandomUUID();

        $data = array(
            'added_by_uuid' => $this->adminSecret,
            'comment' => $comment,
            'current_usage_GB' => 0,
            'last_online' => null,
            'last_reset_time' => null,
            'mode' => $resetMod,
            'name' => $name,
            'package_days' => $package_days,
            'start_date' => date('Y-m-d'),
            'telegram_id' => $telegram_id,
            'usage_limit_GB' => $package_size,
            'uuid' => $uuid
        );

        $data_string = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if ($result == null) {
            return $uuid;
        } else {
            return false;
        }
    }

    private function findElementByUuid(array $data, string $uuid)
    {
        foreach ($data as $value) {
            if ($value['uuid'] == $uuid) {
                return $value;
            }
        }
        return null;
    }

    private function getDataFromSub(string $uuid): array
    {
        $url = $this->urlUser . $uuid;

        // Extract days and GB remaining
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url . '/sub/');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $raw_data = curl_exec($ch);
        curl_close($ch);

        // Import vless & vemss & trojan servers to array
        $servers = [];
        $lines = explode("\n", $raw_data);

        foreach ($lines as $line) {
            if (strpos($line, 'vless://') === 0 || strpos($line, 'trojan://') === 0 || strpos($line, 'vemss://') === 0) {
                $servers[] = $line;
            }
        }

        return $servers;
    }

    public function getUserdetais(string $uuid): array
    {
        $url = $this->urlAdmin . 'api/v1/user/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        $userdata = $this->findElementByUuid($data, $uuid);
        $userdata['subData'] = $this->getDataFromSub($uuid);

        return $userdata;
    }
}
