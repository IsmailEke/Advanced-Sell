<?php

namespace Sell\form;

use Sell\Sell;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class ItemInformationForm implements Form {
    
    /**
     * @return array
     */
     
    public function jsonSerialize () : array {
        $config = new Config(Sell::getInstance()->getDataFolder() . "sellitems.yml", Config::YAML);
        $text = "§cItem Name §7| §cPrice\n\n";
        foreach ($config->get("Items") as $item) {
            $text .= "§7| §c" . explode(":", $item)[2] . " §7=> §c$" . explode(":", $item)[3] . " §7|\n";
        }
        return [
        "type" => "form",
        "title" => "§cSell §7-> §cItem Information",
        "content" => $text,
        "buttons" => [
        ["text" => "Submit"]
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
	}
}
?>