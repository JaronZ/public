<?php
require_once("config.php");
require_once("Database.php");
$db = new Database();
if ($_SERVER["REQUEST_METHOD"] == "GET" && @$_GET["url"]) {
    $url = $db->singleValueQuery("SELECT `location` FROM redirect WHERE `url` = ?", [$_GET["url"]]);
    if (!$url) {
        die(http_response_code(404));
    }
    $url = "Location: ".$url;
    header($url, true, 303);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = json_decode(file_get_contents("php://input"), true);
    if (!isset($json["url"])) {
        die(http_response_code(400));
    }
    $url = $json["url"];
    $newUrl = substr(base64_encode(sha1(mt_rand())), 0, 7);
    $db->query("INSERT INTO redirect(`url`, `location`) VALUES(?, ?)", [$newUrl, $url]);
    http_response_code(200);
    echo json_encode(["url" => $newUrl]);
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>URL Shortener</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    </head>
    <body>
        <form action="/" method="POST">
            <h1>Paste a URL to shorten it</h1>
            <div>
                <input type="text" name="url" placeholder="URL to shorten" />
                <input type="submit" value="Shorten" />
            </div>
        </form>
        <script src="script.js"></script>
    </body>
</html>