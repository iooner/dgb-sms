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
	
	function segments() {
		$req = $this->query('SELECT * FROM segments');
		
		$items = array();
		$single = 100000;
		
		while($data = $req->fetchArray()) {
			$id = ($data['total'] > 1) ? $data['partid'] : $single++;
			
			$items[$data['number']][$id][$data['part']] = array(
				'number'  => $data['number'],
				'message' => $data['message'],
				'date'    => $data['received'],
				'part'    => $data['part'],
				'total'   => $data['total']
			);
		}
		
		return array_reverse($items, true);
	}
	
	function pending() {
		$req = $this->query('SELECT * FROM pending');
		
		$items = array();
		
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
	
	function benevoles() {
		$req = $this->query('SELECT * FROM bnv');
		
		while($data = $req->fetchArray()) {
			$items[] = array(
				'id'   => $data['id'],
				'nom'  => $data['nom'],
				'prenom' => $data['prenom'],
				'phone' => $data['number'],
				'pause' => $data['pause']
			);
		}
		
		return $items;
	}

	function send($phone, $message) {
                $i = 0;

                $stmt = $this->db->prepare('INSERT INTO pending (number, message) VALUES (:p, :n)');
                        $stmt->bindValue(':p', $phone, SQLITE3_TEXT);
                        $stmt->bindValue(':n', $message, SQLITE3_TEXT);

                // retry
                while($stmt->execute() == false && $i++ < 5);
	}

	function user($phone) {
		$req = $this->query('SELECT * FROM bnv WHERE number = \''.$phone.'\'');

                $items[] = array('nom' => 'Inconnu', 'prenom' => 'Inconnu');

		while($data = $req->fetchArray()) {
			$items[] = array(
				'id'   => $data['id'],
				'nom'  => $data['nom'],
				'prenom' => $data['prenom'],
				'phone' => $data['number'],
				'pause' => $data['pause']
			);
		}
		
		return array_pop($items);
	}
}
?>
