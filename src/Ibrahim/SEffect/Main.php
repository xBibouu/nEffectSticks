<?php
namespace Ibrahim\SEffect;

use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public array $sticks = [];

    protected function onLoad(): void
    {
        $this->saveDefaultConfig();
    }

    # PLUGIN DATANT DE DE 2021
    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register('Commandes', new StickCommand($this));
        $this->loadSticks();
        $this->registerSticks();
        $this->getServer()->getPluginManager()->registerEvents(new StickListener($this), $this);
    }

    private function loadSticks(){
        foreach($this->getConfig()->get("sticks") as $identifier => $array){
            if (isset($array['id'])){
                if(!isset($array['effects']) or empty($array['effects'])){
                    $this->getLogger()->warning("Le stick $identifier n'a pas d'effets");
                }
                $namedtag = new CompoundTag();
                $namedtag->setInt("durabilite", $array["durabilite"]);
                $namedtag->setString("identifier", $identifier);
                $item = StringToItemParser::getInstance()->parse($array["id"]);
                $item->getNamedTag()->setString('identifier', $identifier);
                $item->getNamedTag()->setInt('durabilite', $array["durabilite"]);
                if(isset($array["name"])) $item->setCustomName($array["name"]);
                $lore = $array["lore"] ?? "\n§r§fDurabilité : §6{durabilite}/{max}";
                $item->setLore([str_replace(["{durabilite}", "{max}"], [$array["durabilite"], $array["durabilite"]], $lore)]);
                $stick = new Stick($item, $array["name"], $array["durabilite"], $array["effects"], $array["cooldown"], $lore, $identifier);
                $this->sticks[$identifier] = $stick;
            } else {
                $this->getLogger()->warning('§cErreur, vous avez oublié id au stick ' . $identifier);
            }
        }
    }

    /**
     * @return Stick[]
     */
    public function getSticks() : array {
        return $this->sticks;
    }

    public function getStick(string $identifier) : ?Stick{
        return $this->sticks[$identifier] ?? null;
    }

    public function registerSticks(){
        foreach ($this->getSticks() as $stick){
            StringToItemParser::getInstance()->register($stick->getIdentifier(), function() use ($stick): Item{
                return $stick->getItem();
            });
            CreativeInventory::getInstance()->add($stick->getItem());
        }
    }

}