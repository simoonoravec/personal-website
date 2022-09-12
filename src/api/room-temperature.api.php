<?php
ini_set('default_socket_timeout', 30);
header("Content-type: application/json");
function getSensorData() {
    $data = json_decode(file_get_contents("https://homeapi.0r4v3c.xyz/tempmon/current_temp.php"), true);
    if ($data == false || $data["success"] != true) {
        return [false, 0];
    } else {
        return [true, $data["temp_c"]];
    }
}

function getData() {
    $cache = json_decode(file_get_contents("./cache/roomtemp.json"), true);
    if ($cache["expires"] > time()) {
        $out = $cache["data"];
        $out["cached"] = true;
        return $out;
    } else {
        $data = getSensorData();
        
        $out = ["success"=>$data[0], "temp_c"=>$data[1]];
        file_put_contents("./cache/roomtemp.json", json_encode(["expires"=>time()+60, "data"=>$out]));

        $out["cached"] = false;
        return $out;
    }
}

die(json_encode(getData()));
?>
