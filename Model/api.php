<?php
function fetchMovies($page = 1)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=pt-BR&page={$page}";

    $response = file_get_contents($url);

    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    $data = json_decode($response, true);

    return $data;
}
