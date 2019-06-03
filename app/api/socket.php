<?php
/**
 * 設問 - 登録
 *
 * 設問 - 登録をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2019, Tryit
 * @version 1.00
 * @since 2019/05/07
 */
/**
 例： http://nissan.t4u.bz/nlp/app/api/session/1
      http://nissan.t4u.bz/nlp/app/api/questions_1_a/1/1
      http://nissan.t4u.bz/nlp/app/api/questions_1_r/1/1
      http://nissan.t4u.bz/nlp/app/api/reporting_1_q/1/1
      http://nissan.t4u.bz/nlp/app/api/examinations_1_q
      http://nissan.t4u.bz/nlp/app/api/enquetes_1_q
 */
$sessionDisable = true;
$path = __DIR__ . "/../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require(".site_conf.php");
require("../lib/core/iCommon.php");            // 基本クラス
require(".page_common.php");

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

require(__DIR__ . '/../vendor/autoload.php');

fLog("socket.php start");
echo LOG_FILE . "\n";
/**
  * chat.php
  * Send any incoming messages to all connected clients (except sender)
  */
  class Chat implements MessageComponentInterface {

      protected $clients;

      public function __construct() {
fLog("__construct");
          $this->clients = new \SplObjectStorage;
      }

      public function onOpen(ConnectionInterface $conn) {
fLog("onOpen");
          $this->clients->attach($conn);
      }

      public function onMessage(ConnectionInterface $from, $msg) {
fLog("onMessage");
          foreach ($this->clients as $client) {
              if ($from != $client) {
                  $client->send($msg);
              }
          }
      }

      public function onClose(ConnectionInterface $conn) {
fLog("onClose");
          $this->clients->detach($conn);
      }

      public function onError(ConnectionInterface $conn, \Exception $e) {
fLog("onError");
          $conn->close();
      }
  }

  // Run the server application through the WebSocket protocol on port 8080
  $server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 9000);
//  $server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 8080);
fLog("run");
  $server->run();


echo "test";
