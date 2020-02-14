<?php
/**
 * Generated by Haxe 4.0.5
 */

namespace php\_Boot;

use \php\Boot;

/**
 * Class<T> implementation for Haxe->PHP internals.
 */
class HxClass {
	/**
	 * @var string
	 */
	public $phpClassName;

	/**
	 * @param string $phpClassName
	 * 
	 * @return void
	 */
	public function __construct ($phpClassName) {
		#D:\haxe\haxe\std/php/Boot.hx:651: characters 3-35
		$this->phpClassName = $phpClassName;
	}

	/**
	 * Magic method to call static methods of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $method
	 * @param mixed $args
	 * 
	 * @return mixed
	 */
	public function __call ($method, $args) {
		#D:\haxe\haxe\std/php/Boot.hx:659: characters 3-111
		$callback = ((($this->phpClassName === "String" ? HxString::class : $this->phpClassName))??'null') . "::" . ($method??'null');
		#D:\haxe\haxe\std/php/Boot.hx:660: characters 3-53
		return call_user_func_array($callback, $args);
	}

	/**
	 * Magic method to get static vars of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $property
	 * 
	 * @return mixed
	 */
	public function __get ($property) {
		#D:\haxe\haxe\std/php/Boot.hx:668: lines 668-676
		if (defined("" . ($this->phpClassName??'null') . "::" . ($property??'null'))) {
			#D:\haxe\haxe\std/php/Boot.hx:669: characters 4-54
			return constant("" . ($this->phpClassName??'null') . "::" . ($property??'null'));
		} else if (Boot::hasGetter($this->phpClassName, $property)) {
			#D:\haxe\haxe\std/php/Boot.hx:671: characters 29-41
			$tmp = $this->phpClassName;
			#D:\haxe\haxe\std/php/Boot.hx:671: characters 4-59
			return $tmp::{"get_" . ($property??'null')}();
		} else if (method_exists($this->phpClassName, $property)) {
			#D:\haxe\haxe\std/php/Boot.hx:673: characters 4-48
			return new HxClosure($this->phpClassName, $property);
		} else {
			#D:\haxe\haxe\std/php/Boot.hx:675: characters 33-45
			$tmp1 = $this->phpClassName;
			#D:\haxe\haxe\std/php/Boot.hx:675: characters 4-56
			return $tmp1::${$property};
		}
	}

	/**
	 * Magic method to set static vars of this class, when `HxClass` instance is in a `Dynamic` variable.
	 * 
	 * @param string $property
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function __set ($property, $value) {
		#D:\haxe\haxe\std/php/Boot.hx:684: lines 684-688
		if (Boot::hasSetter($this->phpClassName, $property)) {
			#D:\haxe\haxe\std/php/Boot.hx:685: characters 22-34
			$tmp = $this->phpClassName;
			#D:\haxe\haxe\std/php/Boot.hx:685: characters 4-59
			$tmp::{"set_" . ($property??'null')}($value);
		} else {
			#D:\haxe\haxe\std/php/Boot.hx:687: characters 26-38
			$tmp1 = $this->phpClassName;
			#D:\haxe\haxe\std/php/Boot.hx:687: characters 4-56
			$tmp1::${$property} = $value;
		}
	}
}

Boot::registerClass(HxClass::class, 'php._Boot.HxClass');