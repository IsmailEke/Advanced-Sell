<?php

namespace Sell\command;

use Sell\form\SellForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandException;
use pocketmine\player\Player;

class SellCommand extends Command {

	public function __construct () {
		parent::__construct("sell", "Sell Command.", "/sell");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	
	public function execute (CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$sender->sendForm(new SellForm($sender));
		}
	}
}
?>