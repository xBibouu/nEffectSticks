<?php
namespace Ibrahim\SEffect;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class StickCommand extends Command {

    public function __construct(private readonly Main $main)
    {
        parent::__construct("stick");
        $this->setPermission('stick.command.permission');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (empty($this->main->getSticks())){
                $sender->sendMessage("§cAucun stick");
                return;
            }
            if(isset($args[0])){
                $stick = $this->main->getStick($args[0]);
                if (!is_null($stick)){
                    $stickItem = $stick->getItem()->setCount(1);
                    if ($sender->getInventory()->canAddItem($stickItem)){
                        $sender->getInventory()->addItem($stickItem);
                    } else $sender->getWorld()->dropItem($sender->getPosition(), $stickItem);
                } else $sender->sendMessage("§cLe stick $args[0] n'existe pas");
            } else $sender->sendMessage("§cUsage : /stick (stick)");
        } else $sender->sendMessage("§cUtilisez la commande en jeu");
    }

}