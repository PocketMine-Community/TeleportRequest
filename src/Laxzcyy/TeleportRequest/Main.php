<?php

namespace Laxzcyy\TeleportRequest;

use Laxzcyy\TeleportRequest\command\TeleportRequestCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public static self $instance;

    public function onEnable(): void {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("tpa", new TeleportRequestCommand());
    }


    public function onQuit(PlayerQuitEvent $event): void {
        $name = $event->getPlayer()->getName();

        foreach (TeleportRequestCommand::$requests as $target => $data) {
            if ($target === $name || $data["from"] === $name) {
                unset(TeleportRequestCommand::$requests[$target]);
            }
        }
    }
}