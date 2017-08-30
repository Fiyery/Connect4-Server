<?php
class Socket 
{

    protected $_host = NULL;
    protected $_port = NULL;
    protected $_resource = NULL;
    protected $_clients = NULL;
    protected $_last_index = NULL;

    public function __construct(string $host, int $port)
    {
        $this->_host = $host;
        $this->_port = $port;

        set_time_limit(0);

        if (($this->_resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === FALSE)
        {
            // TODO Exception
        }
        socket_set_option($this->_resource, SOL_SOCKET, SO_REUSEADDR, 1);
    }

    public function bind()
    {
        if (socket_bind($this->_resource, $this->_host, $this->_port) === FALSE)
        {
            // TODO Exception
        }

        $this->_is_connection = FALSE;
    }

    public function listen()
    {
        if (socket_listen($this->_resource) === FALSE) 
        {
            // TODO Exception
        }
    }
    
    public function accept() : int
    {
        if (($client = socket_accept($this->_resource)) === FALSE) 
        {
            
        }
        $this->_clients[] = $client;
        return (count($this->_clients) - 1);     
    }
    
    public function read(int $index = -1) : string 
    {
        $index = ($index === -1) ? (count($this->_clients) - 1) : ($index);
        if ($index < 0 || $index >= count($this->_clients) || is_resource($this->_clients[$index]) === FALSE)
        {
            return "";
        }
        $data = "";
        do 
        {
            $part = socket_read($this->_clients[$index], 1024);
            $data .= $part;
        }
        while ($part === "" || $part === FALSE);
        return $data;
    }
    
    public function write(string $data, int $index = -1) : bool
    {
        if ($this->_is_connection)
        {
            $resource = $this->_resource;
        }
        else
        {
            $index = ($index === -1) ? (count($this->_clients) - 1) : ($index);
            if ($index < 0 || $index >= count($this->_clients) || is_resource($this->_clients[$index]) === FALSE)
            {
                return FALSE;
            }
            $resource = $this->_clients[$index];
        }
        socket_write($resource, $data, strlen($data));
        return TRUE;
    }

    public function connect()
    {
        if (socket_connect($this->_resource, $this->_host, $this->_port) === FALSE)
        {
            // TODO Exception
        }

        $this->_is_connection = TRUE;
    }
    
    public function close()
    {
        socket_close($this->_resource);

        if (is_resource($this->_clients))
        {
            socket_close($this->_clients);
        }
    }
}
?>