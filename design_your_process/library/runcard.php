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

		public function get_all() {
		}

		public function get_by_user() {
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
		public static function create() {
			db_query("INSERT INTO runcard (username, public) VALUES ('', 0);");
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
						$q = "INSERT INTO runcard_inputs (runcard_id, ordering, process_id, input_id, input_value)
							VALUES ('$this->id','$i','$pf->id','$parameter->id','$parameter->value')";
						db_query($q);
					}
				}
				if ($no_inputs) {
						$q = "INSERT INTO runcard_inputs (runcard_id, ordering, process_id)
							VALUES ('$this->id','$i','$pf->id')";
						db_query($q);
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

		private function initialize($sql_results) {
			if ($sql_results) {
				foreach($sql_results[0] as $key => $value) {
					$this->$key = $value;
				}	
			} else {
				throw new Exception("Empty result set in initialize");
			}
		}
	}
?>

