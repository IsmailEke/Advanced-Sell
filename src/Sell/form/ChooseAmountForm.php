<?php

namespace Sell\form;

use Sell\Sell;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;

class ChooseAmountForm implements Form {

	/**
	 * @param array $itemData
	 */
	
	public function __construct (protected array $itemData) {
	}
	
	/**
	 * @return array
	 */
	
	public function jsonSerialize () : array {
		$content = [];
		$content[] = ["type" => "label", "text" => "§7Determine the Amount of the Item You Will Sell.\n\n"];
		foreach ($this->itemData as $itemName => $itemCount) {
			$content[] = ["type"=> "slider", "text" => "§7| §c" . $itemName . "§7", "min" => 1, "max" => $itemCount, "step" => -1];
		}
		return [
		"type" => "custom_form",
		"title" => "§cSell §7-> §cSell Selectively",
		"content" => $content
		];
	}

	/**
	 * @param Player $player
	 * @param mixed $data
	 * 
	 * @return void
	 */
	
	public function handleResponse (Player $player, $data) : void {
		if (is_null($data)) return;
		$id = [];
		$meta = [];
		$price = [];
		$totalPrice = 0;
		$config = new Config(Sell::getInstance()->getDataFolder() . "sellitems.yml", Config::YAML);
		unset($data[0]);
		foreach ($data as $index => $selectedAmount) {
		    foreach ($config->get("Items") as $item) {
		        if (explode(":", $item)[2] === array_keys($this->itemData)[$index - 1]) {
		            $id[] = explode(":", $item)[0];
		            $meta[] = explode(":", $item)[1];
		            $price[] = explode(":", $item)[3];
		        }
		    }
		    $totalPrice += $selectedAmount * $price[$index - 1];
		    $player->getInventory()->removeItem(ItemFactory::getInstance()->get($id[$index - 1], $meta[$index - 1], $selectedAmount));
		}
		EconomyAPI::getInstance()->addMoney($player, $totalPrice);
		$player->sendMessage("§7Certain amount of items you selected have been sold.\n§7Amount of Money Earned:§6 " . $totalPrice . " Dollar");
	}
}
?>