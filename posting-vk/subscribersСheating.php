<?php

include __DIR__ . "/../lib/commonLib.php";
include __DIR__ . "/libVK.php";
include __DIR__ . "/../connect/connect.php";

$getTokenQuery = "SELECT * FROM `token_vk` WHERE app_id=?";
$tok = mysqli_fetch_array(mysqli_query($connect, $getTokenQuery), MYSQLI_ASSOC);
$tokenPost = $tok['token'];

$getPhraseQuery = "SELECT phrase FROM `mutual_subscription` ORDER BY RAND() LIMIT 1";
$phr = mysqli_fetch_array(mysqli_query($connect, $getPhraseQuery), MYSQLI_ASSOC);
$randomPhrase = $phr['phrase'];

$adaptedPhrase = adaptationPhraseForURL($randomPhrase);
$getGroupWithMINSubscriptions = "SELECT group_id FROM `groups_vk` ORDER BY RAND() LIMIT 1";
$groupID = mysqli_fetch_array(mysqli_query($connect, $getGroupWithMINSubscriptions), MYSQLI_ASSOC);
$getAlienGroup = "SELECT group_id FROM `mutual_group_subscription` ORDER BY RAND() LIMIT 1";
$groupIDAlien = mysqli_fetch_array(mysqli_query($connect, $getAlienGroup), MYSQLI_ASSOC);

$version="&v=5.101";
$urlPost = "https://api.vk.com/method/wall.post?owner_id=-179381249&from_group=1&message=" . $adaptedPhrase . "&access_token=" . $tokenPost . $version;
simplePost($urlPost);
mysqli_close($connect);
?>
