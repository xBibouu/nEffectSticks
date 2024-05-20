<?php
namespace Ibrahim\SEffect;

use pocketmine\item\Item;

# PLUGIN DATANT DE DE 2021
class Stick {

    private Item $item;
    private string $stick_name;
    private int $durabilite;
    private array $effects;
    private int $cooldown;
    private string $lore;
    protected string $identifier;

    public function __construct(Item $item, string $stick_name, int $durabilite, array $effects, int $cooldown, string $lore, string $identifier)
    {
        $this->item = $item;
        $this->effects = $effects;
        $this->stick_name = $stick_name;
        $this->cooldown = $cooldown;
        $this->durabilite = $durabilite;
        $this->lore = $lore;
        $this->identifier = $identifier;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @return int
     */
    public function getMaxDurability(): int
    {
        return $this->durabilite;
    }

    /**
     * @return string
     */
    public function getStickName(): string
    {
        return $this->stick_name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return int
     */
    public function getCooldown(): int
    {
        return $this->cooldown;
    }

    /**
     * @return array
     */
    public function getEffects(): array
    {
        return $this->effects;
    }

    /**
     * @return string
     */
    public function getLore(): string
    {
        return $this->lore;
    }

}