<?php
/**
 *
 * 1. Создать структуру классов ведения товарной номенклатуры.
 *  а) Есть абстрактный товар.
 *  б) Есть цифровой товар, штучный физический товар и товар на вес.
 *  в) У каждого есть метод подсчета финальной стоимости.
 *  г) У цифрового товара стоимость постоянная – дешевле штучного 
 *     товара в два раза. У штучного товара обычная стоимость, 
 *     у весового – в зависимости от продаваемого количества 
 *     в килограммах. У всех формируется в конечном итоге доход с продаж.
 *  д) Что можно вынести в абстрактный класс, наследование?
 *  
 * 2. *Реализовать паттерн Singleton при помощи traits.
 *
 */

trait Singleton {
	protected static $_instance;
	
	function __construct() {
		return self::getInstance();
	}
	
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		
		return self::$_instance;
	}
	
	private function __clone() {
	}
	
	private function __wakeup() {
	}
}

abstract class Product {
	
	protected $mName;
	public $mDescription;
	
	protected $mState = [
			'retail_price' => 0,
			'trade_price'  => 0,
			'lenght' => 0,
			'height' => 0,
			'width'  => 0,
			'weight' => 0,
			'count'  => 0
	];
	
	abstract public function getTotalPrice();
	abstract public function getProfit();
	
	public function __construct($name, $price) {
		
		if (empty($name))
			throw new Exception("Имя товара не может иметь пустое значение");
			
			$this->mName = $name;
			$this->price($price);
			
	}
	
	public function __toString() {
		return $this->get();
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

class DigitalProduct extends Product {
	
	public function getTotalPrice() {
		return $this->mState['retail_price'] * $this->mState['count'];
	}
	
	public function getProfit() {
		return $this->getTotalPrice() - ($this->mState['trade_price'] * $this->mState['count']);
	}
	
}

class RealProduct extends Product {
	
	public function getTotalPrice() {
		return $this->mState['retail_price'] * $this->mState['count'];
	}
	
	public function getProfit() {
		return $this->getTotalPrice() - ($this->mState['trade_price'] * $this->mState['count']);
	}
	
}

class WeightProduct extends Product {
	
	public function getTotalPrice() {
		return $this->mState['retail_price'] * $this->mState['weight'];
	}
	
	public function getProfit() {
		return $this->getTotalPrice() - ($this->mState['trade_price'] * $this->mState['weight']);
	}
	
}