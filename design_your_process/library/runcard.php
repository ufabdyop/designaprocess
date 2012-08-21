<?php
 require_once('helper_functions.php');
 
	function get_runcards_for_user($username) {
		$username = addslashes($username);
		$q = "SELECT * FROM runcard WHERE username = '$username'";
	}
        
	class Runcard {
		var $id;
		var $username;
		var $name;
		var $public;
		var $process_forms = array();

                private static function get_subset($where_clause) {
			$q = "select * from runcard where $where_clause";
			$results = db_query_with_column_names($q);
                        $result_set = array();
                        foreach($results as $row) {
                            $rc = new Runcard;
                            $rc->initialize(array($row));
                            $result_set[] = $rc;
                        }
                        return $result_set;
                }
                
		/**
		* 
		 * returns all Runcards from the database 
		 * @return the runcard object array
		 */
                public static function get_all() {
                    return Runcard::get_subset(" 1 = 1 ");
		}
                
                
		/**
		* 
		 * returns all public Runcards from the database 
		 * @return the runcard object array
		 */
                public static function get_all_public() {
                    return Runcard::get_subset(" public = 1 ");
		}
                

		/**
		* 
		 * returns all Runcards from the database with specific username
		 * @param $id: string, the username to filter by
		 * @return the runcard object array
		 */
                public static function get_by_username($id) {
                    return Runcard::get_subset("username = '$id'");
		}
                
                public function get_process_forms() {
                    return $this->process_forms;
                }

		/**
		* 
		 * returns existing Runcard from the database
		 * @param $id: int, the id of the runcard that was saved 
		 * @return the runcard object
		 */
		public static function get_by_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}	
			$q = "select * from runcard where id = $id";
			$results = db_query_with_column_names($q);
			$rc = new Runcard;
			$rc->initialize($results);
			return $rc;
		}

		/**
		* 
		 * Creates a new Runcard in the database
		 * @return int, the id of the runcard that was saved 
		 */
		public static function create($username = '', $name = '', $public = 0) {

			/*
			$sql = "INSERT INTO runcard (username, name, public) VALUES ('$username', '$name', '$public');";
			db_query($sql);
			*/
			named_query('create_runcard', array(':username' => $username, ':name' => $name, ':public' => $public));
			
			$id = last_insert_id();
			return Runcard::get_by_id($id);
		}

		public function save() {
			//delete processes from runcard_inputs table if they've been removed from object
			db_query("DELETE FROM runcard_inputs where runcard_id = '$this->id'");

			//insert new processes into runcard_inputs
			for($i = 0; $i < count($this->process_forms);  $i++) {
				$no_inputs = true;
				$pf = $this->process_forms[$i];
				$inputs = $pf->inputs();
				foreach($inputs as $parameter) {
					if ($parameter->value) {
						$no_inputs = false;
                                                named_query('runcard_insert_inputs', array(
                                                                ':id' => $this->id,
                                                                ':ordering' => $i,
                                                                ':process_id' => $pf->id,
                                                                ':input_id' => $parameter->id,
                                                                ':input_value' => $parameter->value) );
					}
				}
				if ($no_inputs) {
                                                named_query('runcard_insert__no_inputs', array(
                                                                ':id' => $this->id,
                                                                ':ordering' => $i,
                                                                ':process_id' => $pf->id));
				}
			}
		}


		/**
		* 
		 * Removes the current record from the DB
		 */
		public function remove() {
			if ($this->id) {
				db_query("DELETE FROM runcard WHERE id = '$this->id'");
                                db_query("DELETE FROM runcard_inputs WHERE runcard_id = '$this->id'");
			}
		}

		/**
		* add a process_form to a runcard
		*
		*/
		public function add_process_form($process_form) {
			$this->process_forms[] = $process_form;
		}

		public function remove_process_form() {
		}
                
                public function reorder_process_forms($ordering = array()) {
                    //first check that the ordering contains all process forms
                    $existing_ids = array();
                    $existing_forms_indexed = array();
                    foreach($this->process_forms as $pf) {
                        $existing_ids[] = $pf->id;
                        $existing_forms_indexed[$pf->id] = $pf;
                    }
                    $intersection = array_intersect($existing_ids, $ordering);
                    $complete_overlap = false;
                    if (count($intersection) == count($existing_ids)) {
                        $complete_overlap = true;
                    }
                    
                    if (!$complete_overlap) {
                        throw new Exception("Reordering array does not contain all process ids");
                    }
                    
                    $this->process_forms = array();
                    foreach($ordering as $id) {
                        $this->process_forms[] = $existing_forms_indexed[$id];
                    }
                }
                
                public function remove_step($process_id, $order) {
                    $process = $this->process_forms[$order];
                    if ($process->id != $process_id) {
                        throw new Exception("Order does not match process id");
                    }
                    array_splice($this->process_forms, $order, 1);
                    
                }

		private function initialize($sql_results) {
			if ($sql_results) {
				foreach($sql_results[0] as $key => $value) {
					$this->$key = $value;
				}	
			} else {
				throw new Exception("Empty result set in initialize");
			}
                        
                        //add associated process_forms and inputs
                        $runcard_id = $sql_results[0]['id'];
                        
                        $sql = "SELECT * FROM runcard_inputs where runcard_id = '$runcard_id' order by ordering, process_id";
                        $rows = db_query_with_column_names($sql);
                        $last_process = false;
                        $last_ordering = false;
                        $new_process = false;
                        foreach($rows as $row) {
                            $process_id = $row['process_id'];
                            $ordering = $row['ordering'];
                            if ($last_process != $process_id || $last_ordering != $ordering) {
                                if ($new_process) {
                                    $this->add_process_form($new_process);
                                }
                                $new_process = Process_form::get_by_id($process_id);
                            }
                            if ($row['input_id'] != null) {
                                $new_process->set_parameter_by_id($row['input_id'], $row['input_value']);
                            }
                            $last_process = $process_id;
                            $last_ordering = $ordering;
                        }
                        if ($new_process) {
                            $this->add_process_form($new_process);
                        }
		}
	}
?>

