<?php
require "lib/Socket.class.php";
$socket = new Socket("127.0.0.1", 1234);
$socket->connect();

$socket->write("Hello !");
echo $socket->read();

echo "\n\nSocket fermé !\n";

$socket->close();