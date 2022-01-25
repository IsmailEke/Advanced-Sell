<?php

/*
YouTube: Ismail Eke
*/

namespace Sell;

use Sell\command\SellCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Sell extends PluginBase {
	
	private static Sell $instance;

	public function onLoad () {
		self::$instance = $this;
	}

	/**
	 * @return Sell
	 */
	
	public static function getInstance () : Sell {
		return self::$instance;
	}

	public function onEnable () : void {
		$this->getLogger()->notice("Sell Plugin Online");
		$this->saveResource("sellitems.yml");
		$this->getServer()->getCommandMap()->register("sell", new SellCommand());
	}

	public function onDisable () : void {
		$this->getLogger()->alert("Sell Plugin Offline");
	}
}
?>
