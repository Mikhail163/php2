<?php

if (isset($_POST['current_path']))
	$current_dir = $_POST['current_path'];
else
	$current_dir = '.';

$dir_id = -1;
	
foreach ($_POST as $key => $value)
	// If a submit button was clicked ...
	if (substr($key, 0, 11) == 'submit-path')
	{
			/* Get the position of the last '_' underscore from submit button name
			 e.g strtpos('submit_edit_prod_1', '_') is 17 */
		$last_underscore = strrpos($key, '_');
			
			/* Get the scope of submit button
			 (e.g  'edit_dep' from 'submit_edit_prod_1') */
		$action = substr($key, strlen('submit-path_'),
					$last_underscore - strlen('submit-path_'));
			
			/* Get the product id targeted by submit button
			 (the number at the end of submit button name)
			 e.g '1' from 'submit_edit_prod_1' */
		$dir_id= (int)substr($key, $last_underscore + 1);
			
		break;
	}


echo "<h1>Текущая дирректория: $current_dir</h1>";
echo '<form method="post" style="display:flex; flex-direction: column; width:200px;">';

if ($dir_id >= 0) {
	$temp_dir .= './'.$_POST["submit-path_{$dir_id}"];
	$current_dir = is_dir($temp_dir)?$temp_dir:$current_dir;
}

// Создаем новый объект DirectoryIterator
$dir = new DirectoryIterator($current_dir);

echo '<input type="hidden" value="'.$current_dir.'" name="current_path"/>';
// Цикл по содержанию директории
foreach ($dir as $key=>$item) {
	echo '<input type="submit" value="'.$item.'" name="submit-path_'.$key.'"/>';
	
}

echo '</form>';