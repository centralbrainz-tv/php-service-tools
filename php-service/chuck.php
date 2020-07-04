<?php

set_time_limit(600);
$seconds_to_cache = 604800;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
$params = [];
$params[0] = 'index';
$params[1] = 'index';
$params[3] = '5';
$params[4] = '10000';
$string = file_get_contents("../ready.json");
/*$handle = @fopen("../out.json", "r");

$string = "";
if ($handle) {
    while (($buffer = fgets($handle, 131072)) !== false) {
        $string = $string . $buffer;
    }
    fclose($handle);
}
*/
$json_a = json_decode($string, true);

/*
header("Expires: $ts");
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
*/
header('Content-Type: application/json');

$json_o = [];
$json_y = [];

$i = 0;
foreach ($json_a as $rkey => $resource) {
    if ($params[0] === 'rating') {
        $imdbRating = $resource['imdb']['rating'];
        if (round(floatval($imdbRating)) == round(floatval($params[1]))) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'year') {
        $year = $resource['title'];
        if (stripos($year, $params[1]) !== false) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'years') {
        $year = $resource['title'];
        $json_y[$i] = $year;
        $i++;
    }

    if ($params[0] === 'genre') {
        $genre = explode(", ", $params[1]);
        $genreIMDB = explode(", ", $resource['imdb']['genre']);
        if (count(array_intersect($genre, $genreIMDB)) > 0) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'movie') {
        $name = $resource['name'];
        if ($name === $params[1]) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'search') {
        $name = $resource['name'];
        if (stripos($name, $params[1]) !== false) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'fulltext') {
        $synopsis = [];
        $plot = [];
        $synopsis = $resource['imdb']['arrayPlotSummary'][0]['text'];
        if (stripos($synopsis, $params[1]) !== false) {
            $json_o[$i] = $resource;
            $i++;
        }
    }

    if ($params[0] === 'index') {
        $json_o[$i] = $resource;
        $i++;
    }
}

if ($params[0] === 'year') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $a['name'] < $b['name'] ? 1 : -1;
    });
}

if ($params[0] === 'rating') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $b['imdb']['rating'] < $a['imdb']['rating'] ? 1 : -1;
    });
}

if ($params[0] === 'genre') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $a['name'] < $b['name'] ? 1 : -1;
    });
}

if ($params[0] === 'search') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $a['name'] < $b['name'] ? 1 : -1;
    });
}

if ($params[0] === 'fulltext') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $a['name'] < $b['name'] ? 1 : -1;
    });
}

if ($params[0] === 'index') {
    $order = -1;
    usort($json_o, function ($b, $a) {
        return $b['imdb']['count'] < $a['imdb']['count'] ? 1 : -1;
    });
}

if ($params[0] === 'years') {
    $json_o = array_filter(array_unique($json_y));
    usort($json_o, function ($b, $a) {
        return $a < $b ? 1 : -1;
    });
}
$json_o = [
    'count' => count($json_o),
    'result' => array_slice(
        $json_o,
        $params[3] * $params[4] - $params[4],
        $params[4]
    ),
];

echo json_encode($json_o);
?>
