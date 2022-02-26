<?php

namespace AokoMoney;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Inventory;
use pocketmine\level\Level;
use pocketmine\entity\human;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;

class Main extends PluginBase implements Listener{

    public function onEnable(): void{
        if(!file_exists($this->getDataFolder() . "MoneySystem/")){
            @mkdir($this->getDataFolder() . "MoneySystem/");
        }
        $this->aokomoney = new Config($this->getDataFolder() . "MoneySystem/" . "Data.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if($this->aokomoney->exists($player->getName())){
        }else{
            $this->aokomoney->set($player->getName(), ["VNĐ" => 1000, "Coins" => 0, "Tokens" => 0, "Points" => 0]);
            $this->aokomoney->save();
        }
    }
    public function getAokoMoneyData(Player $player, string $type){
        switch($type){
            case "VNĐ":
                return $this->aokomoney->get($player->getName())["VNĐ"];
            case "Coins":
                return $this->aokomoney->get($player->getName())["Coins"];
            case "Tokens":
                return $this->aokomoney->get($player->getName())["Tokens"];
            case "Points":
                return $this->aokomoney->get($player->getName())["Points"];
        }
    }
    public function addAokoMoneyData(Player $player, string $type, int $amount){
        $vnd = $this->getAokoMoneyData($player, "VNĐ");
        $coins = $this->getAokoMoneyData($player, "Coins");
        $tokens = $this->getAokoMoneyData($player, "Tokens");
        $points = $this->getAokoMoneyData($player, "Points");
        switch($type){
            case "VNĐ":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd + $amount, "Coins" => $coins, "Tokens" => $tokens, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Coins":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins + $amount, "Tokens" => $tokens, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Tokens":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins, "Tokens" => $tokens + $amount, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Points":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins, "Tokens" => $tokens, "Points" => $points + $amount]);
                $this->aokomoney->save();
                break;
        }
    }
    public function reduceAokoMoneyData(Player $player, string $type, int $amount){
        $vnd = $this->getAokoMoneyData($player, "VNĐ");
        $coins = $this->getAokoMoneyData($player, "Coins");
        $tokens = $this->getAokoMoneyData($player, "Tokens");
        $points = $this->getAokoMoneyData($player, "Points");
        switch($type){
            case "VNĐ":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd - $amount, "Coins" => $coins, "Tokens" => $tokens, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Coins":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins - $amount, "Tokens" => $tokens, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Tokens":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins, "Tokens" => $tokens - $amount, "Points" => $points]);
                $this->aokomoney->save();
                break;
            case "Points":
                $this->aokomoney->set($player->getName(), ["VNĐ" => $vnd, "Coins" => $coins, "Tokens" => $tokens, "Points" => $points - $amount]);
                $this->aokomoney->save();
                break;
        }
    }
}
