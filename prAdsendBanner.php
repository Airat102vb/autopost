<?

include('communitiesList.php');

$apiUrl="https://api.vk.com/method/wall.post?owner_id=";

$version="&v=5.92";

$tday=date(j); //get day number (type string)
settype($tday, integer); //change string variable type to int.

if($tday%2==0) {
    $communities=$communitiesList;
} else {
    $communities=$largeCommunities;
}

for($i=0; $i<=count($communities); $i++){
    $vkowner=$communities[$i][0];
    $ulink=$communities[$i][1];
    $ch = curl_init($apiUrl.$vkowner."&attachments=photo?,".$ulink.$version);

    curl_exec($ch);
    curl_close($ch);
    sleep(1);
}

?>
