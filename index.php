<?php 

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$baseUrl = 'http://api.openweathermap.org';
$appid = '61a14e96edd8e3b8976925d213a73894';
$id = '3468879';

//Recupera a data de criação dos dados
$dataCriacao = file_get_contents('cache/validade_tempo.txt');

//300 = 5min
if(time() - $dataCriacao >= 300){

try{
$client = new Client(array('base_uri' => $baseUrl));

$response = $client->get('/data/2.5/weather', array(
    'query' => array('appid' => $appid, 'id' => $id)
));

$tempo = json_decode($response->getBody());
$dadosSerializados = serialize($tempo);
file_put_contents('cache/dados_tempo.txt', $dadosSerializados);
file_put_contents('cache/validade_tempo.txt', time());


}catch (ClientException $e){
    echo 'Falha ao obter informações';
}

}else{
    $dadosSerializados = file_get_contents('cache/dados_tempo.txt');
    $tempo = unserialize($dadosSerializados);
}

$celsius = ($tempo->main->temp-273);
$pressao = ($tempo->main->pressure);
$umidade = ($tempo->main->humidity);
$temp_min = ($tempo->main->temp_min-273);
$temp_max = ($tempo->main->temp_max-273);

$temp_convertido = (($temp_min + $temp_max) /2);

print_r('A temperatuda em celsius é: '.$celsius);
echo "<br>";
print_r('A pressão é: '.$pressao);
echo "<br>";
print_r('A umidade é: '.$umidade);
echo "<br>";
print_r('A temperatura minima é: '.$temp_min);
echo "<br>";
print_r('A temperatura maxima é: '.$temp_max);
echo "<br>";
print_r('A media entre as temps maxima e minima é: '.$temp_convertido);

?>