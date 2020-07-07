<?php

namespace dokidia;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;

class Reservation extends PluginBase implements Listener {
   public $db, $database;
   public function onEnable() {
      $this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
      @mkdir ( $this->getDataFolder () );
      $this->database = new Config ( $this->getDataFolder () . "database.yml", Config::YAML );
      $this->db = $this->database->getAll ();
   }
   public function save() {
      $this->database->setAll ( $this->db );
      $this->database->save ();
   }
   public function onJoin(PlayerJoinEvent $event) {
      $player = $event->getPlayer ();
      $name = $player->getName ();
      if (! isset ( $this->db [$name] )) {
         $this->db [$name] = [ ];
         $this->db [$name] ["reservation"] = "none";
      }
   }
   public function onCommand(CommandSender $sender, Command $command, $label, array $args):bool {
      $command = $command->getName ();
      $name = $sender->getName ();
      $prefix = TextFormat::AQUA . "[ 사전예약 ] " . TextFormat::WHITE;
      if ($command == "사전예약코어") {
         if (! isset ( $args [0] )) {
            $sender->sendMessage ( $prefix . "/사전예약 [ 등록 | 취소 ]" );
            return true;
         }
               switch ($args [0]) {
                  case "등록" :
                     $this->db [$name] ["reservation"] = "yes";
                     $this->save ();
                     $sender->sendMessage ( $prefix . "성공적으로 사전예약을 진행하였습니다" );
                     return true;
                     break;

                  case "취소" :
                     $this->db [$name] ["reservation"] = "cancel";
                     $this->save ();
                     $sender->sendMessage ( $prefix . "사전예약을 취소했습니다" );
                     return true;
                     break;

               }

      }
   }

   /*
   public function onHit(EntityDamageEvent $ev) {
      $entity = $ev->getEntity ();
      if ($ev instanceof EntityDamageByEntityEvent) {
         $damager = $ev->getDamager ();
         if ($entity instanceof Player && $damager instanceof Player) {
            $ename = $entity->getName ();
            $dname = $damager->getName ();
            if (isset ( $this->db [$ename] ["reservation"] )) {
               if ($damager->isSneaking ()) {
                  if ($this->db [$ename] ["reservation"] == "여자") {
                     $damager->sendMessage ( "§e§l$ename §r§f님의 성별 : §l§d여자" );
                  } elseif ($this->db [$ename] ["reservation"] == "남자") {
                     $damager->sendMessage ( "§e§l$ename §r§f님의 성별 : §l§b남자" );
                  } elseif ($this->db [$ename] ["reservation"] == "미설정") {
                     $damager->sendMessage ( "§e§l$ename §r§f님의 성별 : §l§7설정하지 않음" );
                  }
               }
            }
         }
      }
   }

   */
   public function onDisable() {
      $this->save ();
   }
}