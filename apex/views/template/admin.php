<?php
// Вернет массив запросов
$query = $this->db->queries;
$query_times = $this->db->query_times;
$sumaQtimes = 0;

foreach($query as $k=>$q)
{
    //echo substr($query_times[$k], 0, 7).' : '.$q."\n";
    $sumaQtimes += $query_times[$k];
}

echo "\t".'Общее количество запросов: '.count($query)."\n";
echo "\t".'Время выполнения запросов: '.substr($sumaQtimes, 0, 5)."\n";
echo '-->'; 
?>