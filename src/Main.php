<?php

declare(strict_types=1);

namespace Tatsomi\ShutdownUI;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase{

    public Config $config;

    protected function onEnable(): void
    {
        $this->getLogger()->notice("plugin is enabled!");

        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    protected function onDisable(): void
    {
        $this->getLogger()->notice("plugin is disabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() == "sdui") {
            if ($sender instanceof Player) {
                $this->sdui($sender);
            } else {
                $sender->sendMessage("Please use this command in-game!");
            }
            return true;
        }
        return false;
    }

    public function sdui(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data !== null) {
                if ($data === 0) {
                    $player->getServer()->forceShutdown();
                } elseif ($data === 1) {
                    $player->sendMessage($this->config->get("message"));
                }
            }
        });

        $form->setTitle($this->config->get("title"));
        $form->setContent($this->config->get("content"));
        $form->addButton($this->config->get("button0"));
        $form->addButton($this->config->get("button1"));

        $form->sendToPlayer($player);

    }
}
