<?php
date_default_timezone_set("Asia/Dhaka");
$logFile = "log.txt";

function log_message($message) {
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

log_message("Video generation started.");

$wordText = file_get_contents("text.txt");
log_message("Loaded text: " . trim($wordText));

$pexelsApiKey = "fOuF5nlf1UEsPIEuAeOjNeXpGXZAMCoxJWwNvyMkCyLWB8Oku5LZ4dpQ";
$query = urlencode(explode(":", $wordText)[0]);
$pexelsUrl = "https://api.pexels.com/v1/search?query=$query&per_page=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pexelsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: $pexelsApiKey"]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$imageUrl = $data["photos"][0]["src"]["large2x"] ?? null;

if ($imageUrl) {
  file_put_contents("image.jpg", file_get_contents($imageUrl));
  log_message("Image downloaded from: $imageUrl");
} else {
  log_message("No image found from Pexels API.");
  die("No image found from Pexels API");
}

$imagePath = "image.jpg";
$audioPath = "default.mp3";
$outputPath = "today.mp4";

$cmd = "ffmpeg -loop 1 -i {$imagePath} -i {$audioPath} -vf drawtext='text={$wordText}:fontcolor=white:fontsize=24:x=(w-text_w)/2:y=(h-text_h)/2:box=1:boxcolor=black@0.5:boxborderw=5' -shortest -y {$outputPath}";
log_message("Running command: $cmd");

exec($cmd, $output, $resultCode);

if ($resultCode === 0) {
    log_message("Video created successfully.");
    echo "Video created successfully.";
} else {
    log_message("Video creation failed with code: $resultCode");
    echo "Video creation failed.";
}
?>