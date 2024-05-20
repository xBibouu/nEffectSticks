<?php
namespace Ibrahim\SEffect;

use JetBrains\PhpStorm\Pure;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\world\sound\ItemBreakSound;

class StickListener implements Listener {

    private Main $main;
    private array $cooldown = [];

    #[Pure] public function __construct(Main $main)
    {
        $this->main = $main;
        foreach ($main->getSticks() as $stick) $this->cooldown[$stick->getIdentifier()] = [];
    }

    # PLUGIN DATANT DE DE 2021
    public function use(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if ($item->getNamedTag()->getTag("identifier") !== null){
            $stick = $this->main->getStick($item->getNamedTag()->getString("identifier"));
            if (!is_null($stick)){
                $cooldown = (array)$this->cooldown[$stick->getIdentifier()];
                if (isset($cooldown[$player->getName()])){
                    if ($cooldown[$player->getName()] - time() <= 0){
                        $this->useStick($player, $item, $stick);
                    } else $player->sendPopup(str_replace("{SEC}", $cooldown[$player->getName()] - time(), $this->main->getConfig()->get("cooldown-msg")));
                } else $this->useStick($player, $item, $stick);
            }
        }
    }

    # PLUGIN DATANT DE DE 2020
    private function useStick(Player $player, Item $item, Stick $stick)
    {
        if(!empty($stick->getEffects())){
            foreach ($stick->getEffects() as $effect_id => $effect_parameters){
                $player->getEffects()->add(new EffectInstance(EffectIdMap::getInstance()->fromId($effect_id), 20 * $effect_parameters["time"], $effect_parameters["amplifier"]));
            }
        }
        $player->sendPopup($this->main->getConfig()->get("actived"));
        $newDurabilite = $item->getNamedTag()->getInt('durabilite') - 1;
        if ($newDurabilite > 0){
            $newItem = clone $item;
            $newItem->getNamedTag()->setInt('durabilite', $newDurabilite);
            $newItem->setLore([str_replace(["{durabilite}", "{max}"], [$newDurabilite, $stick->getMaxDurability()], $stick->getLore())]);
            $player->getInventory()->setItemInHand($newItem);
        } else{
            $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1));
            $player->getWorld()->addSound($player->getPosition(), new ItemBreakSound());
        }
        $this->cooldown[$stick->getIdentifier()][$player->getName()] = time() + $stick->getCooldown();
    }

}