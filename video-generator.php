<?php
require_once("pexels-image.php");

function selectBGM($keyword) {
    $musicFolder = "bg-music/";
    $bgms = [
        "calm" => "calm.mp3",
        "power" => "power.mp3",
        "nature" => "nature.mp3"
    ];
    foreach ($bgms as $key => $file) {
        if (stripos($keyword, $key) !== false) {
            return $musicFolder . $file;
        }
    }
    return $musicFolder . "default.mp3";
}

$words = json_decode(file_get_contents("words.json"), true);
$today = date("z") % count($words);
$entry = $words[$today];

$imageUrl = getImageFromPexels($entry['word']);
file_put_contents("image.jpg", file_get_contents($imageUrl));
file_put_contents("text.txt", "{$entry['word']}\n{$entry['definition_en']}\n{$entry['sentence_en']}\n{$entry['definition_bn']}\n{$entry['sentence_bn']}");

$bgm = selectBGM(strtolower($entry['word']));
$cmd = "ffmpeg -loop 1 -i image.jpg -i $bgm -vf drawtext='text=text.txt:fontcolor=white:fontsize=24:x=(w-text_w)/2:y=(h-text_h)/2:box=1:boxcolor=black@0.5:boxborderw=5' -shortest -y output/today.mp4";

$output = [];
$return_var = 0;
exec($cmd . " 2>&1", $output, $return_var);

echo "<pre>";
echo "FFmpeg Command:\n$cmd\n\n";
echo "Output:\n" . implode("\n", $output) . "\n\n";
echo "Exit Code: $return_var";
echo "</pre>";
?>