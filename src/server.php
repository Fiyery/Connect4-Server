<?php
require "lib/Socket.class.php";
$socket = new Socket("127.0.0.1", 1234);
$socket->bind();
$socket->listen();

echo "Socket en attente ...\n";

$socket->accept();

echo $socket->read();
$socket->write("Hello You !");

$socket->close();

echo "\nSocket fermé !\n";
?>