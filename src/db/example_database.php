<?php
require_once __DIR__ . '/../vendor/autoload.php';
define("TOOL_HOST", "http://localhost:9090");
session_start();

use \IMSGlobal\LTI;

$_SESSION['iss'] = [];
$reg_configs = array_diff(scandir(__DIR__ . '/configs'), array('..', '.', '.DS_Store'));

foreach ($reg_configs as $key => $reg_config) {
    $_SESSION['iss'] = array_merge(
        $_SESSION['iss'], 
        json_decode(file_get_contents(__DIR__ . "/configs/$reg_config"), true)
    );
}

class Example_Database implements LTI\Database {
    public function find_registration_by_issuer($iss) {
        if (empty($_SESSION['iss']) || empty($_SESSION['iss'][$iss])) {
            return false;
        }

        $config = $_SESSION['iss'][$iss];

        return LTI\LTI_Registration::new()
            ->set_auth_login_url($config['auth_login_url'])
            ->set_auth_token_url($config['auth_token_url'])
            ->set_auth_server($config['auth_server'] ?? null) // Corrigido
            ->set_client_id($config['client_id'])
            ->set_key_set_url($config['key_set_url'])
            ->set_kid($config['kid'] ?? null) // Corrigido
            ->set_issuer($iss)
            ->set_tool_private_key($this->private_key($iss));
    }

    public function find_deployment($iss, $deployment_id) {
        if (empty($_SESSION['iss'][$iss]['deployment']) || 
            !in_array($deployment_id, $_SESSION['iss'][$iss]['deployment'])) {
            return false;
        }

        return LTI\LTI_Deployment::new()
            ->set_deployment_id($deployment_id);
    }

    private function private_key($iss) {
        return file_get_contents(__DIR__ . $_SESSION['iss'][$iss]['private_key_file']);
    }
}
?>
