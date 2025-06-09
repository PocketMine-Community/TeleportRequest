<?php

namespace Laxzcyy\TeleportRequest\command;

use Laxzcyy\TeleportRequest\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class TeleportRequestCommand extends Command
{
    
    /** @var array<string, array{from: string, expires: int}> */
    public static array $requests = [];
    
    public function __construct()
    {
        parent::__construct("tpa", "Işınlanma isteği komutu", "/tpa", ["tpak", "tpar"]);
        $this->setPermission(DefaultPermissionNames::GROUP_USER);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            switch ($commandLabel) {
                case "tpa":
                    foreach (self::$requests as $target => $data) {
                        if ($data["from"] === $sender->getName()) {
                            $sender->sendMessage("§cZaten bekleyen bir isteğin var.");
                            return true;
                        }
                    }

                    if (empty($args[0])) {
                        $sender->sendMessage("§cKullanım: /tpa <oyuncu>");
                        return true;
                    }

                    $searchName = strtolower(implode(" ", $args));
                    $target = null;
                    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                        if (str_starts_with(strtolower($player->getName()), $searchName)) {
                            $target = $player;
                            break;
                        }
                    }

                    if ($target instanceof Player && $target !== $sender) {
                        self::$requests[$target->getName()] = [
                            "from" => $sender->getName(),
                            "expires" => time() + 60
                        ];

                        $sender->sendMessage("§a" . $target->getName() . " adlı oyuncuya istek gönderildi.");
                        $target->sendMessage("§e" . $sender->getName() . " sana ışınlanma isteği gönderdi.\n§a» /tpaccept\n§c» /tpdeny");

                        Main::$instance->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($target) {
                            if (isset(self::$requests[$target->getName()])) {
                                $from = self::$requests[$target->getName()]["from"];
                                $requester = Server::getInstance()->getPlayerExact($from);
                                if ($requester instanceof Player) {
                                    $requester->sendMessage("§cİsteğin zaman aşımına uğradı.");
                                }
                                unset(self::$requests[$target->getName()]);
                            }
                        }), 20 * 60);
                    } elseif ($target === $sender) {
                        $sender->sendMessage("§cKendine istek gönderemezsin.");
                    } else {
                        $sender->sendMessage("§cOyuncu bulunamadı.");
                    }
                    break;

                case "tpak":
                    if (isset(self::$requests[$sender->getName()])) {
                        $from = self::$requests[$sender->getName()]["from"];
                        $requester = Server::getInstance()->getPlayerExact($from);
                        if ($requester instanceof Player) {
                            $requester->teleport($sender->getPosition());
                            $requester->sendMessage("§aİsteğin kabul edildi!");
                            $sender->sendMessage("§aIşınlanma isteğini kabul ettin.");
                        }
                        unset(self::$requests[$sender->getName()]);
                    } else {
                        $sender->sendMessage("§cSana gönderilmiş bir istek yok.");
                    }
                    break;
                case "tpar":
                    if (isset(self::$requests[$sender->getName()])) {
                        $from = self::$requests[$sender->getName()]["from"];
                        $requester = Server::getInstance()->getPlayerExact($from);
                        if ($requester instanceof Player) {
                            $requester->sendMessage("§cİsteğin reddedildi.");
                        }
                        unset(self::$requests[$sender->getName()]);
                        $sender->sendMessage("§aIşınlanma isteğini reddettin.");
                    } else {
                        $sender->sendMessage("§cSana gönderilmiş bir istek yok.");
                    }
                    break;
            }
        } else {
            $sender->sendMessage("§cBu komutu sadece oyuncular kullanabilir.");
            return false;
        }
    }
}
