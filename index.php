<?

set_time_limit(120);
$start = microtime(true);


$xmlFile = 'export-1h01-assets-26-03-06.xml' ;

$domDoc = new DOMDocument();
$domDoc->load($xmlFile);
$rows = $domDoc->getElementsByTagName('Row');

echo $rows->count, ' rows', PHP_EOL;

$db = new SQLite3('db/assets_ds.db',SQLITE3_OPEN_READWRITE ); //SQLITE3_OPEN_READONLY

 


echo '<pre>';


foreach ($rows as $row) { 

	$cells = $row->getElementsByTagName('Cell');

//var_dump($cells);

    $values = array();
	foreach ($cells as $cell) { 
		$values[] = $cell->nodeValue;
	//print_r($cell);
	}

//print_r($values);


list(
		$v_os,				//	[0] => Основний засіб
		$v_os_sub, 			//	[1] => Підномер
		$v_description, 	//	[2] => Найменування основного засобу
		$v_description1, 	//	[3] => Найменування основного засобу
		$v_description2, 	//	[4] => Опис основного засобу (2)
		$v_inv, 			//	[5] => Інвентарний номер
		$v_sn, 				//	[6] => Серійний номер
		$v_do,				//	[7] => Дата оприбуткування
		$v_dd, 				//	[8] => Дата деактивації
		$v_dz, 				//	[9] => Дата зміни
		$v_classoz,			// 	[10] => Клас ОЗ
		$v_bs, 				//	[11] => Бізнес-сфера
		$v_qt, 				//	[12] => Кількість
		$v_mvz, 			//	[13] => Міс.вин.в.
		$v_mp, 				//	[14] => Местоположение ОС
		$v_mpo, 			//	[15] => ОС: МОЛ
		$v_fio, 			//	[16] => Текст
		$v_inv_old			//	[17] => Муніципалітет
) = $values;


if (substr($v_classoz, 0, 2) != '10') continue;

$v_os = "'{$v_os}'";
$v_os_sub = "'{$v_os_sub}'";



$v_description = "{$v_description1}{$v_description2}";
$v_description = str_replace(array("'","\""), "", $v_description);
$v_description = "'{$v_description}'";

// $v_description = $db->escapeString($v_description);

//$v_description = sqlite_escape_string (null, $v_description);


$v_inv = "'{$v_inv}'"; 
$v_sn = "'{$v_sn}'";

if (($timestamp = strtotime($v_do)) === false) {
    $v_do = 'NULL';
} else {
    $v_do = date('d.m.Y', $timestamp);
    $v_do = "'{$v_do}'";
}

if (($timestamp = strtotime($v_dd)) === false) {
    $v_dd = 'NULL';
} else {
    $v_dd = date('d.m.Y', $timestamp);
    $v_dd = "'{$v_dd}'";
}

if (($timestamp = strtotime($v_dz)) === false) {
    $v_dz = 'NULL';
} else {
    $v_dz = date('d.m.Y', $timestamp);
    $v_dz = "'{$v_dz}'";
}

$v_classoz = "'{$v_classoz}'";
$v_bs = "'{$v_bs}'";
//$v_qt = $v_qt;
$v_mvz = "'{$v_mvz}'";
$v_mp = "'{$v_mp}'";

$v_mpo = "'{$v_mpo}'";
$v_fio = "'{$v_fio}'";
$v_inv_old = "'{$v_inv_old}'";


/*
$v_os, $v_os_sub, $v_description, $v_inv, $v_sn, $v_do, $v_dd, $v_dz, $v_classoz, $v_bs, $v_qt, $v_mvz, $v_mp, $v_mpo, $v_fio, $v_inv_old
*/

$sql = "REPLACE INTO \"main\".\"assets_ds\" (\"os\", \"os_sub\", \"description\", \"inv\", \"sn\", \"do\", \"dd\", \"dz\", \"classoz\", \"bs\", \"qt\", \"mvz\", \"mp\", \"mpo\", \"fio\", \"inv_old\") 
VALUES ($v_os, $v_os_sub, $v_description, $v_inv, $v_sn, $v_do, $v_dd, $v_dz, $v_classoz, $v_bs, $v_qt, $v_mvz, $v_mp, $v_mpo, $v_fio, $v_inv_old);";

/*
VALUES (10000000001, 0, 'Ноутбук HP ProBook 6550 b', '010000000001/0000', 'CNU1091X53', '06.04.2016', NULL, '30.01.2017', 10401009, '1H01', 1, '1H01201920', '1H012010', 70068395, 'CNU1091X53', 'ДКС-104200000093/000');
*/

$results = $db->query($sql);

if ($results === FALSE ) { 
	echo $sql, PHP_EOL;
} 
else {
	echo $v_inv, ' - ok', PHP_EOL;
}


} 


$execution_time = (microtime(true) - $start)/60;

echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';


?>