<?php
error_reporting(E_ALL ^ E_WARNING);
class hiddifyApi
{
    private $mainUrl, $path, $adminPath;
    // private $systemStats = $mainUrl ."get_data/";

    function __construct($mainUrl, $path, $adminPath)
    {

        $url = $mainUrl . '/' . $path . '/' . $adminPath . '/admin/get_data/';
        $response = json_decode(file_get_contents($url), true);

        $this->mainUrl = $mainUrl;
        $this->path = $path;
        $this->adminPath = $adminPath;

        if (!is_array($response)) {
            die('Can`t connect to hiddify');
        }
        //! return system stats
        return $response;
    }

    public function getUserdetais($uuid): array
    {
        $url = $this->mainUrl . '/' . $this->path . '/' . $uuid . '/all.txt';
        // $url = $mainUrl.'/all.txt';
        $raw_data = file_get_contents($url);


        // Extract days and GB remaining
        preg_match('/([0-9.]+)GB_Remain:([0-9]+)days/', $raw_data, $matches);
        $info = [
            'GB_Remain' => (float) $matches[1],
            'days' => (int) $matches[2],
        ];

        // Import vless & vemss & trojan servers to array
        $servers = [];
        $lines = explode("\n", $raw_data);

        foreach ($lines as $line) {
            if (strpos($line, 'vless://') === 0 || strpos($line, 'trojan://') === 0 || strpos($line, 'vemss://') === 0) {
                $servers[] = $line;
            }
        }
        $user = [
            'info' => $info,
            'server' => $servers
        ];

        return $user;
    }

    public function getcrftoken(): string
    {
        // Load the HTML content into a DOMDocument object
        $url = $this->mainUrl . '/' . $this->path . '/' . $this->adminPath . '/admin/user/';

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

    public function adduser(string $uuid, string $name, int $usage_limit_GB, int $package_days, string $comment = ''): void
    {
        $url = $this->mainUrl . '/' . $this->path . '/' . $this->adminPath . '/admin/user/new/';

        $formData = array(
            'csrf_token' => $this->getcrftoken(),
            'uuid' => $uuid,
            'name' => $name,
            'usage_limit_GB' => $usage_limit_GB,
            'package_days' => $package_days,
            'mode' => 'no_reset',
            'comment' => $comment,
            'enable' => 'y',
            'reset_days' => '',
            'reset_usage' => ''
        );
        var_dump($formData, $url);
        // Initialize a cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        // Close the cURL session
        curl_close($ch);
    }

    public function getUserList(): array
    {
        $url = $this->mainUrl . '/' . $this->path . '/' . $this->adminPath . '/admin/user/';
        $htmlContent = file_get_contents($url);

        $DOM = new DOMDocument();
        $DOM->loadHTML($htmlContent);

        $Header = $DOM->getElementsByTagName('th');
        $Detail = $DOM->getElementsByTagName('td');

        //#Get header name of the table
        foreach ($Header as $NodeHeader) {
            $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
        }
        //print_r($aDataTableHeaderHTML); die();

        //#Get row data/detail table without header name as key
        $i = 0;
        $j = 0;
        foreach ($Detail as $sNodeDetail) {
            $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
            $i = $i + 1;
            $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
        }
        //print_r($aDataTableDetailHTML); die();

        //#Get row data/detail table with header name as key and outer array index as row number
        for ($i = 0; $i < count($aDataTableDetailHTML); $i++) {
            for ($j = 0; $j < count($aDataTableHeaderHTML); $j++) {
                $aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
            }
        }
        $aDataTableDetailHTML = $aTempData;
        unset($aTempData);
        return ($aDataTableDetailHTML);
    }
}

$api = new hiddifyApi('https://sus-1.senpai-like-you.autos', 'u1ixLfIaGlcy3bcbolkNy', 'fbac8b45-e518-4b35-affb-9d93b9637806');
var_dump($api->getUserList());
// var_dump($api->getUserdetais('9d82b181-0ffe-4ebf-98d9-e2699c59428f'));
