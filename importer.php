<?php

$ids = array();

$handle = @fopen("data.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        if($result = parseData($buffer)) {
        	$ids[] = $result;
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

echo json_encode($ids);




function parseData($data) {
	$url_parts = parse_url($data);
	$path_parts = explode("/", $url_parts['path']);
	if(count($path_parts) > 3) {

		$number = trim(str_replace("_", "", $path_parts[3]));

		if(is_numeric($number)) {
			return getFacebookID($number);
		} else {
			// return "something is wrong: " . $path_parts[3] . "\n";
		}
		
	} else {
		$name = trim(str_replace("_", "", $path_parts[1]));
		return getFacebookID($name);
	}
} //parseData


function getFacebookID($name) {
	$response = file_get_contents_curl("https://graph.facebook.com/{$name}");
	$result = json_decode($response);

	$facebookID = trim($result->id);

	if($facebookID !== "")
		return $facebookID;
	else
		return; // return "Error getting Facebook ID for {$name}.\n";
}

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}