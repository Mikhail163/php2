<?php


class Book extends Product {
	public $mAuthor = '';
	
	public function __construct($name, $price, $author, $page_number) {
		parent::__construct($name, $age);
		
		$this->mAuthor = $author;
		
		parent::$mState['page_number'] = 0;
		
		parent::state('page_number', $page_number);
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
	
	private function state($state_name = '', $val = -1) {
		
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