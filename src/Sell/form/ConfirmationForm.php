<?php

namespace Sell\form;

use onebone\economyapi\EconomyAPI;
use Sell\Sell;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;

class ConfirmationForm implements Form {

	/**
	 * @return array
	 */
	
	public function jsonSerialize () : array {
		return [
		"type" => "modal",
		"title" => "§cSell §7-> §cConfirm Menu",
		"content" => "\n§7Do you accept the '§cSell All Sellable Items§7'?",
		"button1" => "§aI Agree",
		"button2" => "§cI Refuse"
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
		if ($data) {
			$id = [];
			$meta = [];
			$itemName = [];
			$price = [];
			$totalMoney = 0;
			$confirmedItems = [];
			$sellitems = new Config(Sell::getInstance()->getDataFolder() . "sellitems.yml", Config::YAML);
			foreach ($sellitems->get("Items") as $items) {
				$id[] = explode(":", $items)[0];
				$meta[] = explode(":", $items)[1];
				$itemName[] = explode(":", $items)[2];
				$price[] = explode(":", $items)[3];
			}
			foreach ($player->getInventory()->getContents() as $item) {
				if (in_array($item->getId(), $id) and in_array($item->getMeta(), $meta)) {
				    $totalMoney += $item->getCount() * array_combine($id, $price)[$item->getId()];
				    $confirmedItems[] = array_combine($id, $itemName)[$item->getId()];
					$player->getInventory()->removeItem(ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), $item->getCount()));
				}
			}
			EconomyAPI::getInstance()->addMoney($player->getName(), $totalMoney);
			$text = "§7- §6Items Sold §7-\n";
			for ($i = 0; $i < count(array_unique($confirmedItems)); $i++) {
			    $text .= "§7 => §6" . array_unique($confirmedItems)[array_keys(array_unique($confirmedItems))[$i]] . "\n";
			}
			$player->sendMessage($text . "§7Total Money Earned: §6" . $totalMoney . " Dollar");
		}
	}
}
?>