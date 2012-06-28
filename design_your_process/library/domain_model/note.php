<?php

require_once( DSPPATH . 'library/pdo.php');

class Note {
    public $author;
    public $note;
    private $date_modified;
    private $id;
    private $process_id;
    public $active = 1;
    
    function get_id() {
        return $this->id;
    }
    
    function get_date_modified() {
        return $this->date_modified;
    }
    
    function set_process_id($id) {
        //do not allow changing the process id if it is already assigned
        if ($this->process_id) {
            return;
        }
        $this->process_id = $id;
    }
    
    private function save_new() {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO process_notes (process_id, author, note, date_modified, active) values ('$this->process_id', '$this->author', '$this->note', '$now', '$this->active')";
//        echo $sql;
        db_query($sql);
    }
    
    private function save_existing() {
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE process_notes SET
                    author = '$this->author', 
                    note = '$this->note', 
                    date_modified = '$now',
                    active = '$this->active'
                WHERE id = '$this->id'
                ";
        
        db_query($sql);
    }
    
    private function set_by_db_row($row) {
                $this->author = $row['author'];
                $this->note = $row['note'];
                $this->id = $row['id'];
                $this->process_id = $row['process_id'];
                $this->date_modified = $row['date_modified'];
                $this->active = $row['active'];
    }
    
    public function get_by_id($id) {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = "SELECT * FROM process_notes where id = '$id'";
        $rows = db_query($sql);
        if ($rows) {
            if ($rows[0]) {
                $row = $rows[0];
                $this->set_by_db_row($row);
            }
        }
    }
    
    public function get_by_process_id($id) {
        if (!is_numeric($id)) {
            return null;
        }
        $sql = "SELECT * FROM process_notes where id = '$id'";
        $rows = db_query($sql);
        $notes = array();
        if ($rows) {
            foreach($rows as $row) {
                $note = new Note();
                $note->set_by_db_row($row);
                $notes[] = $note;
            }
        }
        return $notes;
    }
    
    public function save() {
        if ($this->id) {
            $this->save_existing();
        } else {
            $this->save_new();
        }
    }
    
    public function delete() {
        $this->active = 0;
        $this->save();
    }
    
}

?>
