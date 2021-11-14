<?php

/*
YouTube: Ismail Eke
*/

namespace Sell;

use Sell\command\SellCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;
use pocketmine\utils\Config;

class Sell extends PluginBase {
    
	/** @var Sell */
	
	private static $instance;

	public function onLoad () {
		self::$instance = $this;
	}

	/**
	 * @return Sell
	 */
	
	public static function getInstance () : Sell {
		return self::$instance;
	}

	public function onEnable () {
		MainLogger::getLogger()->notice("Sell Plugin Online");
		$this->saveResource("sellitems.yml");
		$this->getServer()->getCommandMap()->register("sell", new SellCommand());
	}

	public function onDisable () {
		MainLogger::getLogger()->alert("Sell Plugin Offline");
	}
}
?>