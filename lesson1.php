<?php
/**
 * Урок 1 PHP 2
 * @author Уваров Михаил
 *
 */

$book = new Book('Крутая книга', 1000, 'Пушкин', 521);

$book->view();

class Book extends Product {
	public $mAuthor = '';
	
	public function __construct($name, $price, $author, $page_number) {
		parent::__construct($name, $price);
		
		$this->mAuthor = $author;
		
		$this->mState['page_number'] = 0;
		
		$this->state('page_number', $page_number);
	}
}

class Product {
	
	protected $mName;
	public $mDescription;
	
	protected $mState = [
			'retail_price' => 0,
			'trade_price'  => 0,
			'lenght' => 0,
			'height' => 0,
			'width'  => 0,
			'weight' => 0
	];
	
	public function __construct($name, $price) {
		
		if (empty($name))
			throw new Exception("Имя товара не может иметь пустое значение");
			
			$this->mName = $name;
			$this->price($price);
			
	}
	
	public function get() {
		
		$product = "Название: {$this->mName}; описание: {$this->mDescription};";
		
		foreach ($this->mState as $key => $value) {
			if ($value != 0)
				$product .= " {$key}: {$value};";
		}
		
		return $product;
	}
	
	public function view() {
		echo $this->get();
	}
	
	public function name($name = '') {
		
		if (empty($name))
			return $this->mName;
			else
				$this->mName = $name;
	}
	
	
	public function price($price=-1) {
		
		return $this->state('retail_price', $price);
		
	}
	
	public function tradePrice($price=-1) {
		
		return $this->state('trade_price', $price);
		
	}
	
	public function lenght($lenght=-1) {
		
		return $this->state('lenght', $price);
		
	}
	
	public function height($height=-1) {
		
		return $this->state('height', $price);
		
	}
	
	public function width($width=-1) {
		
		return $this->state('width', $price);
		
	}
	
	public function weight($weight=-1) {
		
		return $this->state('weight', $price);
		
	}
	
	protected function state($state_name = '', $val = -1) {
		
		if (!empty($state_name)) {
			
			if (isset($this->mState[$state_name])) {
				if ($val < 0 || !is_numeric($val))
					return $this->mState[$state_name];
					else
						$this->mState[$state_name] = $val;
			}
			else {
				throw new Exception("{$state_name} Неизвестное свойство.");
			}
			
		}
		else
			throw new Exception('Не указано название свойства.');
	}
	
}

class A {
	public function foo() {
		static $x = 0;
		echo ++$x;
	}
}

/*
 * Поскольку переменная $x статична в классе
 * то во всех объектах этого класса это ссылка на один и тот же
 * участок памяти, где хранится переменная
 * Поэтому все объекта этого класса будут обращаться к одной и той же переменной
 */

echo '<p>';

$a1 = new A();
$a2 = new A();
$a1->foo();
$a2->foo();
$a1->foo();
$a2->foo();

echo '</p>';

/*
 * undeclared static property действуют только в пределах одного класса
 * если класс изменяется - то соответсвенно undeclared static property
 * будут лежать в другом участке памяти, пренадлежащему другому классу
 */
echo '<p>';
class B extends A {
	
}
$a1 = new A;
$b1 = new B;
$a1->foo();
$b1->foo();
$a1->foo();
$b1->foo(); 

echo '<p>';
