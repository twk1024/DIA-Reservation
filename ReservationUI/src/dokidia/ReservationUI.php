<?php

namespace dokidia;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\entity\Effect;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\item\Item;
use pocketmine\lang\BaseLang;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use jojoe77777\FormAPI;

use dokidia\ReservationUI;

use onebone\economyapi\EconomyAPI;

class ReservationUI extends PluginBase implements Listener {

    /** @var ReservationUI $instance */
    private static $instance;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
        $this->getLogger()->info(TextFormat::GREEN . "DIA-ReservationUI by 도끼다이아");
	}
	
	public static function getInstance() : self{
	    return self::$instance;
	}
 

       
    public function onCommand(CommandSender $o, Command $kmt, string $label, array $array) : bool{
        if($kmt->getName() == "사전예약"){
            $this->Menu($o);
        }
        return true;

    
	}
	
    public function Menu($sender){ 
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, int $data = null) {
            $result = $data;
            if($result === null){
                return true;
            }             
            switch($result){
				case 0:
				$this->kor($sender);
				break;
                case 1:
                $this->eng($sender);
                break;

                        

                }
            });
            $name = $sender->getName();
           
            $form->setTitle("§l다이아서버 | 사전예약");
			$form->setContent("§f현재 이 서버는 오픈 준비중이며\n§f사전예약을 받고 있습니다\n§f");
			$form->addButton("§b§l[ §0사전예약 등록§b ]",0,"textures/ui/arrow_active");
            $form->addButton("§b§l[ §0사전예약 취소§b ]",0,"textures/ui/arrow_active");
            $form->sendToPlayer($sender);
            return $form;
    }


	public function kor($player){
                    $command = "사전예약코어 등록";
                    $this->getServer()->getCommandMap()->dispatch($player, $command);
	}


	public function eng($player){
		$command = "사전예약코어 취소";
                    $this->getServer()->getCommandMap()->dispatch($player, $command);
	}
	



}

?>
