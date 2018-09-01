<?php

/**
 * 1. Написать аналог «Проводника» в Windows для директорий на сервере при помощи итераторов.
 * 2. Попробовать определить, на каком объеме данных применение итераторов становится выгоднее, 
 * 	  чем использование чистого foreach.
 * 3.*Создать PHP-демон, который принимает от пользователя сообщения. 
 *    Создать отдельный интерфейс с кнопкой, возвращающей самое старое 
 *    сообщение на экран и удаляющее его. Базы данных, файлы и иные хранилища не использовать.
 */


// Задание 2
$count = 50000;
$test = new TestArray;

for ($i = 1; $i < 5; $i++)
{
	$count *= $i;
	$test->init($count);
	
	echo "<p>{$test->go()}</p>";
}

// Задание 2 экспеимент foraech vs iterator
class TestArray {
	private $mItemCount;
	private $mArray;
	
	
	public function init($count) {
		$this->mItemCount = $count;
		$this->createArray();
	}
	
	private function createArray() {
		unset($this->mArray);
		
		$this->mArray = [];
		
		for ($i = 0; $i < $this->mItemCount; $i++) {
			$this->mArray[$i] = md5($i.time());
		}
			
	}
	
	public function go() {
		
		if ($this->mItemCount == 0) 
			echo '<p>Массив для сравнения пуст</p>';
		
			
		$iterartor_time = 	microtime(true); 
		$this->iterator();
		$iterartor_time = 	microtime(true) - $iterartor_time; 
		
		$array_time = 	microtime(true);
		$this->iterator();
		$array_time = 	microtime(true) - $array_time; 
		
		
		$K = $array_time/$iterartor_time;
		$diff = $array_time - $iterartor_time;
		
		$result = "Размер массива [{$this->mItemCount}]; время выполнения: итератор [{$iterartor_time}]; foreach [{$array_time}] // t_массива/t_итератора = {$K} // t_массива-t_итератора = {$diff}";
		
		return $result;
	}
	
	/**
	 * Тестируем итератор на одних и тех же данных
	 */
	private function iterator() {
		
		$obj = new ArrayObject( $this->mArray );
		
		$iter = $obj->getIterator();
		
		// Цикл для обработки объекта
		while( $iter->valid() )
		{
			if ($iter->key() === $iter->current())
				echo 'hello world';
				
				$iter->next();
		}
		
		$end = microtime(true); 
		
	}
	
	/**
	 * Тестируем массив
	 */
	private function array() {
		
		foreach($this->mArray as $key => $value);
			if ($key === $value)
				echo 'hello world';
		
	}
}

/*
$arr = ["Moscow", "Munich", "Beijing", "Roma", "Barcelona", "London"];
$obj = new ArrayObject( $arr );
$iter = $obj->getIterator();
// Цикл для обработки объекта
while( $iter->valid() )
{
	echo $iter->key() . "=" . $iter->current() . "\n";
	$iter->next();
}

$arr = [
		["sitepoint", "phpmaster"],
		["buildmobile", "rubysource"],
		["designfestival", "cloudspring"],
		"not an array"
];
$iter = new RecursiveArrayIterator($arr);
// Цикл по объекту
// Нужно создать экземпляр RecursiveIteratorIterator
foreach(new RecursiveIteratorIterator($iter) as $key => $value) {
	echo $key . ": " . $value . "<br>";
}
*/
// Задание 1

/*
$explorer = new Explorer;
echo $explorer->render();
*/

/**
 * Класс "Проводник" - просматриваем дирректории при помощи итератора
 * @author Уваров Михаил
 *
 */
class Explorer {
	private $mCurrentDir = '.';
	private $mCurrentLevel = 0;
	private $mDirId = -1;
	private $mFileContent = '';
	
	function __construct() {
		if (isset($_POST['current_path'])) {
			$this->mCurrentDir = $_POST['current_path'];
			$this->mCurrentLevel = $_POST['current_level'];
		}
		
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
				$this->mDirId= (int)substr($key, $last_underscore + 1);
				
				break;
			}
		
		if ($this->mDirId > 1) {
			$temp_dir = $this->mCurrentDir.'/'.$_POST["submit-path_{$this->mDirId}"];
			
			if (is_dir($temp_dir)) {
				$this->mCurrentDir = $temp_dir;
				$this->mCurrentLevel++;
			}
			else
			{
				$this->mFileContent= htmlspecialchars(file_get_contents($temp_dir));
			}
			
		}
		elseif ($this->mDirId== 1 && $this->mCurrentLevel >= 0) {
			// нажали назад
			if ($this->mCurrentLevel > 0) {
				$last_underscore = strrpos($this->mCurrentDir, '/');
				$this->mCurrentDir= substr($this->mCurrentDir, 0, $last_underscore);
				
			}
			else {
				if ($this->mCurrentLevel == 0) {
					$this->mCurrentDir = './..';
				}
			}
			
			$this->mCurrentLevel--;
			
		}
	}
	
	public function render() {
		
		$result = "<h1>Текущая дирректория: {$this->mCurrentDir}</h1>";
		$result .= '<form method="post" style="display:flex; flex-direction: column; width:200px;">';
		
		// Создаем новый объект DirectoryIterator
		$dir = new DirectoryIterator($this->mCurrentDir);
		
		$result .='<input type="hidden" value="'.$this->mCurrentDir.'" name="current_path"/>';
		$result .='<input type="hidden" value="'.$this->mCurrentLevel.'" name="current_level"/>';
		// Цикл по содержанию директории
		foreach ($dir as $key=>$item) {
			if ($key > 0)
				$result .='<input type="submit" value="'.$item.'" name="submit-path_'.$key.'"/>';
				
		}
		
		$result .='</form>';
		if (!empty($this->mFileContent)) {
			$result .="<p><pre>{$this->mFileContent}</pre></p>";
		}
		
		return $result;
	}
}