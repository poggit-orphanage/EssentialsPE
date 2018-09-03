<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers;

use EssentialsPE\BaseFiles\BaseEventHandler;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\CommandEvent;

class OtherEvents extends BaseEventHandler{
    /**
     * @param CommandEvent $event
     */
    public function onServerCommand(CommandEvent $event): void{
        $command = $this->getAPI()->colorMessage($event->getCommand());
        if($command === false){
            $event->setCancelled(true);
        }
        $event->setCommand($command);
    }

    /**
     * @param EntityExplodeEvent $event
     */
    public function onTNTExplode(EntityExplodeEvent $event): void{
        if($event->getEntity()->namedtag->getName() === "EssPE"){
            $event->setBlockList([]);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     *
     * @priority HIGH
     */
    public function onBlockTap(PlayerInteractEvent $event): void{// PowerTool
        if($this->getAPI()->executePowerTool($event->getPlayer(), $event->getItem())){
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     *
     * @priority HIGH
     */
    public function onBlockPlace(BlockPlaceEvent $event): void{
        // PowerTool
        if($this->getAPI()->executePowerTool($event->getPlayer(), $event->getItem())){
            $event->setCancelled(true);
        }

        // Unlimited block placing
        elseif($this->getAPI()->isUnlimitedEnabled($event->getPlayer())){
            $hand = $event->getPlayer()->getInventory()->getItemInHand();
            $hand->setCount($hand->getCount() + 1);
            $event->getPlayer()->getInventory()->setItemInHand($hand);
        }
    }
}
