<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
session_start();

require_once __DIR__ . DIRECTORY_SEPARATOR . 'configs.php';
if (!($configs = getConfig())) {
    die();
}

$apiKey = $configs['apiKey'];
$apiUrl = $configs['apiUrl'];
$siteDomain = $_SERVER['SERVER_NAME'];

/**
 * @param $method
 * @param $data
 * @param $files
 * @return bool|string|void
 */
function post($method, $data = [], $files = [])
{
    global $apiKey, $apiUrl;
    try {
        if (!is_array($data)) {
            throw new \Exception('Данные должны быть в массиве');
        }

        $data['api_key'] = $apiKey;
        $data = array_map(function ($item) {
            return is_array($item) ? json_encode($item) : $item;
        }, $data);
        // file_put_contents('log.txt', print_r($data, true) . PHP_EOL, FILE_APPEND);

        foreach ($files as $field => $file) {
            $tmpfile = $file['tmp_name'];
            $data[$field] = new CURLFile(realpath($tmpfile), $file['type'], $file['name']);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $output = curl_exec($ch);
    } catch (Exception $oException) {
        sendError(500, $oException->getMessage());
        exit();
    } finally {
        curl_close($ch);
    }

    return $output;
}

/**
 * @return mixed
 */
function callMethod($method, $data = [], $files = [])
{
    $responseJson = post($method, $data, $files);
    $responseData = json_decode($responseJson, JSON_UNESCAPED_UNICODE);
    return $responseData['data'];
}

/**
 * @param $data
 * @param $errorCode
 * @return void
 */
function sendResponse($data, $errorCode = null)
{
    header('Content-Type: text/json');
    if ($errorCode) {
        $data = ['code' => $errorCode, 'desc' => $data];
    }
    $result = ['error' => $errorCode ? 1 : 0, 'data' => $data];
    echo json_encode($result);
}

/**
 * @param $code
 * @param $desc
 * @return void
 */
function sendError($code, $desc)
{
    sendResponse($desc, $code);
}


$allowedMethods = [
    'getUsers',
    'getSchedule',
    'getClinics',
    'getProfessions',
    'getServices',
    'getServiceCategories',
    'getPatientAppointments',
    'getPatientPrograms',
    'getPatientFiles',
    'getPatientDocuments',
    'uploadPatientFile',
    'getPatientFileDetails',
    'getPatientLabResultDetails',
    'getPatientDocumentDetails',
    'getPatientLabResults',
    'getPatientConsultations',
    'deletePatientConsultationMessage',
    'addPatientConsultationMessage',
    'getPatientConsultationMessages',
    'getPatientConsultationDetails',
    'getPatientProgramDetails',
    'getPatientInfo',
    'authPatient',
    'checkAuthCode',
    'createAppointment',
    'createInvoice',
    'sendSMS',
    'getInvoices',

    'downloadDocument',
    'sendEmail',
    'getPromos',
    'getSettings',
];

////////////////////////////////////////////////////////////////////////////////

$proxyPath = $_REQUEST['path'] ?? '';
if ($proxyPath) {
    $urlParts = parse_url($apiUrl);
    $baseUrl = "{$urlParts['scheme']}://{$urlParts['host']}";
    if (isset($urlParts['port'])) {
        $baseUrl .= ':'.$urlParts['port'];
    }

    // print_r($urlParts);
    // echo $baseUrl;

    $name = $baseUrl . $proxyPath . '?domain=' . $siteDomain;
    $ext = pathinfo($proxyPath, PATHINFO_EXTENSION);
    $fp = fopen($name, 'rb');

    header("Content-Type: " . mimeByExt($ext));
    //header("Content-Type: image/jpg");
    //header("Content-Length: " . filesize($name));

    fpassthru($fp);
    exit;
}

////////////////////////////////////////////////////////////////////////////////

$method = $_REQUEST['method'] ?? '';
header("Content-Type: application/json; charset=UTF-8");

if (!in_array($method, $allowedMethods)) {
    sendError(404, 'Метод не найден');
    die();
}

//file_put_contents('log.txt', print_r($_FILES, true) . PHP_EOL, FILE_APPEND);
$data = callMethod($method, $_REQUEST, $_FILES);

if ($method == 'getPatientFileDetails') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $data['url']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $fileContent = curl_exec($ch);
    if (curl_errno($ch)) {
        $data['curl_error'] = curl_error($ch);
    }
    curl_close($ch);

    $data['file_content'] = base64_encode($fileContent);
}

sendResponse($data);
exit();

/**
 * @param $ext
 * @return string
 */
function mimeByExt($ext) {
    $mime_map = [
        '3g2' => 'video/3gpp2',
        '3gp' => 'video/3gp',
        '7zip' => 'application/x-compressed',
        'aac' => 'audio/x-acc',
        'ac3' => 'audio/ac3',
        'ai' => 'application/postscript',
        'aif' => 'audio/aiff',
        'au' => 'audio/x-au',
        'avi' => 'video/avi',
        'bin' => 'application/x-binary',
        'bmp' => 'image/bmp',
        'cdr' => 'application/cdr',
        'cpt' => 'application/mac-compactpro',
        'crl' => 'application/pkix-crl',
        'crt' => 'application/x-x509-ca-cert',
        'css' => 'text/css',
        'csv' => 'text/comma-separated-values',
        'dcr' => 'application/x-director',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dvi' => 'application/x-dvi',
        'eml' => 'message/rfc822',
        'exe' => 'application/x-msdownload',
        'f4v' => 'video/x-f4v',
        'flac' => 'audio/x-flac',
        'flv' => 'video/x-flv',
        'gif' => 'image/gif',
        'gpg' => 'application/gpg-keys',
        'gtar' => 'application/x-gtar',
        'gzip' => 'application/x-gzip',
        'html' => 'text/html',
        'ico' => 'image/x-icon',
        'jpeg' => 'image/jpeg',
        'js' => 'text/javascript',
        'json' => 'text/json',
        'kml' => 'application/vnd.google-earth.kml+xml',
        'kmz' => 'application/vnd.google-earth.kmz',
        'log' => 'text/x-log',
        'm4a' => 'audio/mp4',
        'm4u' => 'application/vnd.mpegurl',
        'mid' => 'audio/midi',
        'mif' => 'application/vnd.mif',
        'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'mp3' => 'audio/mp3',
        'mp4' => 'video/mp4',
        'mpeg' => 'video/mpeg',
        'oda' => 'application/oda',
        'ogg' => 'audio/ogg',
        'otf' => 'font/otf',
        'pdf' => 'application/pdf',
        'pem' => 'application/x-pem-file',
        'pgp' => 'application/pgp',
        'php' => 'text/php',
        'png' => 'image/png',
        'ppt' => 'application/powerpoint',
        'doc' => 'application/msword',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'psd' => 'image/vnd.adobe.photoshop',
        'ra' => 'audio/x-realaudio',
        'ram' => 'audio/x-pn-realaudio',
        'rar' => 'application/rar',
        'rpm' => 'audio/x-pn-realaudio-plugin',
        'rsa' => 'application/x-pkcs7',
        'rtf' => 'text/rtf',
        'rtx' => 'text/richtext',
        'rv' => 'video/vnd.rn-realvideo',
        'sit' => 'application/x-stuffit',
        'smil' => 'application/smil',
        'srt' => 'text/srt',
        'svg' => 'image/svg+xml',
        'swf' => 'application/x-shockwave-flash',
        'tar' => 'application/x-tar',
        'tgz' => 'application/x-gzip-compressed',
        'tiff' => 'image/tiff',
        'ttf' => 'font/ttf',
        'txt' => 'text/plain',
        'vcf' => 'text/x-vcard',
        'vlc' => 'application/videolan',
        'vtt' => 'text/vtt',
        'wav' => 'audio/wav',
        'wbxml' => 'application/wbxml',
        'webm' => 'video/webm',
        'webp' => 'image/webp',
        'wma' => 'audio/x-ms-wma',
        'wmlc' => 'application/wmlc',
        'wmv' => 'video/x-ms-wmv',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'xhtml' => 'application/xhtml+xml',
        'xl' => 'application/excel',
        'xls' => 'application/xls',
        'xlsx' => 'application/vnd.ms-excel',
        'xml' => 'application/xml',
        'xsl' => 'text/xsl',
        'xspf' => 'application/xspf+xml',
        'z' => 'application/x-compress',
        'zip' => 'application/zip',
    ];

    return $mime_map[$ext] ?? 'text/javascript';
}
