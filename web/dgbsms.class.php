<?php
class DGBSMS {
    private $db;
    
    function __construct() {
		$this->db = new SQLite3('pending.sqlite3');
		$this->db->busyTimeout(10000);
    }
    
    function query($sql) {
        $req = $this->db->query($sql) or die($this->db->lastErrorMsg());		
		return $req;
    }
    
    function messages() {
        $req = $this->query('SELECT * FROM messages');
        
        $items = array();
        
        while($data = $req->fetchArray()) {
            $items[] = array(
                'id'   => $data['id'],
                'number'  => $data['number'],
                'message' => $data['message'],
                'date' => $data['received'],
                'read' => $data['read']
            );
        }
        
        return $items;
    }
    
    function pending() {
        $req = $this->query('SELECT * FROM pending');
        
        while($data = $req->fetchArray()) {
            $items[] = array(
                'id'   => $data['id'],
                'number'  => $data['number'],
                'message' => $data['message'],
                'date' => $data['added'],
                'sent' => $data['sent']
            );
        }
        
        return $items;
    }
}
?>
