<?php
$tabs = array(
	'buildings'		=> array('id' => 1, 'class' => 'buildtab', 'target'=>'',	'name' => 'Новостройки'),
	'resale'		=> array('id' => 2, 'class' => 'resaletab',	 'target'=>'','name' => 'Вторичная'),
	'residential'	=> array('id' => 3, 'class' => 'residtab', 'target'=>'',	'name' => 'Загородная'),
	//'abroad'		=> array('id' => 4, 'class' => 'abroadtab',	'name' => 'Зарубежная'),
	// 'elite'			=> array('id' => 5, 'class' => 'elitetab',	'name' => 'Элитная'),
	'exclusive'		=> array('id' => 4, 'class' => 'exclusivetab', 'target'=>'',	'name' => 'Для инвестиций'),
	'commercial'	=> array('id' => 5, 'class' => 'commtab',	'target'=>'', 'name' => 'Коммерческая'),
	// 'land'			=> array('id' => 7, 'class' => 'landtab',	'name' => 'Земельные массивы'),
	'assignment'	=> array('id' => 6, 'class' => 'assignmenttab', 'target'=>'',	'name' => 'Переуступки'),
	'arenda'		=> array('id' => 7, 'class' => 'arendatab',	'target'=>'', 'name' => 'Аренда'),
    'http://m16-consulting.ru' => array('id' => 8, 'target'=>'_blank', 'class' => 'consultingtab',	'name' => 'Юридические услуги'),
);
/*

<?php
$tabs = array(
	'buildings'		=> array('id' => 1, 'class' => 'buildtab',	'name' => 'Новостройки'),
	'resale'		=> array('id' => 2, 'class' => 'resaletab',	'name' => 'Вторичная'),
	'residential'	=> array('id' => 3, 'class' => 'residtab',	'name' => 'Загородная'),
	//'abroad'		=> array('id' => 4, 'class' => 'abroadtab',	'name' => 'Зарубежная'),
	// 'elite'			=> array('id' => 5, 'class' => 'elitetab',	'name' => 'Элитная'),
	'commercial'	=> array('id' => 5, 'class' => 'commtab',	'name' => 'Коммерческая'),
	// 'land'			=> array('id' => 7, 'class' => 'landtab',	'name' => 'Земельные массивы'),
	'assignment'	=> array('id' => 6, 'class' => 'assignmenttab',	'name' => 'Переуступки'),
	'arenda'		=> array('id' => 7, 'class' => 'arendatab',	'name' => 'Аренда'),
	'design'		=> array('id' => 4, 'class' => 'commtab',	'name' => 'Дизайн'),
    'http://m16-consulting.ru' => array('id' => 8, 'class' => 'consultingtab',	'name' => 'Юридические услуги'),
);

?>
<ul class="tabs" style=" display: flex;
    flex-flow: row nowrap;
    align-items: center;
    align-content: space-between;
    justify-content: space-between;">
	<?php foreach ($tabs as $key => $tab) { $active = ($key == $current)?' active':''; ?>
    <?php 
        $separator = '/';
        if ($tab['class'] === 'consultingtab') {
            $separator = '';
        }
		if ($tab['class'] === 'consultingtab') {
            $separator = '';
        }
    ?>
	<li style=" display: inline-block;"><a href="<?php echo $separator.$key; ?>" class="tabcc <?php echo $tab['class'].$active; ?>" id="selcont-<?php echo $tab['id']; ?>"><?php echo $tab['name']; ?></a></li>
	<?php } ?>
</ul>




*/
?>
<ul class="tabs">
	<?php foreach ($tabs as $key => $tab) { $active = ($key == $current)?' active':''; ?>
    <?php 
        $separator = '/';
        if ($tab['class'] === 'consultingtab') {
            $separator = '';
        }
    ?>
	<li><a href="<?php echo $separator.$key; ?>" class="tabcc <?php echo $tab['class'].$active; ?>" target="<?=$tab['target']?>" id="selcont-<?php echo $tab['id']; ?>"><?php echo $tab['name']; ?></a></li>
	<?php } ?>
</ul>