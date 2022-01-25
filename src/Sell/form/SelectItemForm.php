<?php

namespace Sell\form;

use Sell\Sell;
use Sell\form\ChooseAmountForm;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class SelectItemForm implements Form {
   
    public array $itemData = [];
    
    /**
     * @param Player $sender
     */
     
    public function __construct (protected Player $sender) {
    }
    
	/**
	 * @return array
	 */
	
	public function jsonSerialize () : array {
	    $config = new Config(Sell::getInstance()->getDataFolder() . "sellitems.yml", Config::YAML);
	    $id = [];
	    $meta = [];
		foreach ($config->get("Items") as $item) {
		    $id[] = explode(":", $item)[0];
		    $meta[] = explode(":", $item)[1];
		    $this->itemData[explode(":", $item)[2]] = (int)0;
		}
		foreach ($this->sender->getInventory()->getContents() as $item) {
		    $index = array_search($item->getId(), $id);
		    if ($index !== false) {
		        if ($item->getId() == $id[$index] and $item->getMeta() == $meta[$index]) {
		            $this->itemData[array_keys($this->itemData)[$index]] = $this->itemData[array_keys($this->itemData)[$index]] + $item->getCount();
		        }
		    }
		}
		foreach ($this->itemData as $itemName => $itemCount) {
		    if ($itemCount == (int)0) {
		        unset($this->itemData[$itemName]);
		    }
		}
		$content = [];
		$content[] = ["type" => "label", "text" => "§7Select Items You Want To Sell."];
		foreach ($this->itemData as $itemName => $itemCount) {
		    $content[] = ["type" => "toggle", "text" => "§7| §c" . $itemName . " §7|", "default" => false];
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
		foreach ($data as $index => $boolean) {
		    if ($boolean === false or $boolean === null) {
		        unset($data[$index]);
		    }
		}
		if (count($data) > 0) {
		    $dataPacket = [];
		    foreach ($data as $indexNumber => $boolean) {
		        $dataPacket[array_keys($this->itemData)[$indexNumber - 1]] = $this->itemData[array_keys($this->itemData)[$indexNumber - 1]];
		    }
		    $player->sendForm(new ChooseAmountForm($dataPacket));
		} else {
		    $player->sendMessage("§cYou Have Not Selected Any Items.");
		}
	}
}
?>