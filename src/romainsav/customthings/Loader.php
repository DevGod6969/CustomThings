<?php

namespace romainsav\customthings;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\plugin\PluginBase;
use romainsav\customthings\items\ItemManager;

class Loader extends PluginBase implements Listener {

    /** @var Loader */
    private static Loader $instance;

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("items.json", true);
        new ItemManager();
    }

    public function onPacketSend(DataPacketSendEvent $event): void {
        foreach ($event->getPackets() as $packet) {
            if ($packet instanceof StartGamePacket) {
                $packet->itemTable = ItemManager::getInstance()->getEntries();
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $player->getNetworkSession()->sendDataPacket(ItemManager::getInstance()->getPacket());
        $player->getNetworkSession()->getInvManager()->syncCreative();
    }

    /**
     * @return Loader
     */
    public static function getInstance(): Loader {
        return self::$instance;
    }
}