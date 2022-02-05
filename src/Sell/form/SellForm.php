<?php

namespace Sell\form;

use Sell\form\ConfirmationForm;
use Sell\form\SelectItemForm;
use Sell\form\ItemInformationForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SellForm implements Form {

	/**
	 * @param Player $sender
	 */
	
	public function __construct (protected Player $sender) {
	}
	
	/**
	 * @return array
	 */
	
	public function jsonSerialize () : array {
		return [
		"type" => "form",
		"title" => "§cSell",
		"content" => "§7Hello§c " . $this->sender->getName() . "\n",
		"buttons" => [
		["text" => "§cSell All Sellable Items\n§7Click It", "image" => ["type" => "path", "data" => "textures/ui/send_icon"]],
		["text" => "§cSell Selectively\n§7Click It", "image" => ["type" => "path", "data" => "textures/ui/send_icon"]],
		["text" => "§cItem Information\n§7Click It", "image" => ["type" => "path", "data" => "textures/ui/creative_icon"]]
		]
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
		switch ($data) {
		    case 0:
		        $player->sendForm(new ConfirmationForm());
		    break;
		    case 1:
		        $player->sendForm(new SelectItemForm($player));
		    break;
		    case 2:
		        $player->sendForm(new ItemInformationForm());
		    break;
		}
	}
}
?>
