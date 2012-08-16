<?php
 require_once('parameter.php');
 require_once('helper_functions.php');
	class Process_form {
		var $id = null;
		var $parameters = array();
		var $process = null;
		
		public static function get_by_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}
			$sql = "SELECT * FROM process_form where process_id = $id";
			$results = db_query_with_column_names($sql);
			$process_form = new Process_form;
			$process_form->initialize($results);
			return $process_form;
		}


		/**
		* 
		 * returns an empty Process_form object
		 * @param process: a process that this form is associated with
		 */
		public static function create($process = null) {
			$pf = new Process_form;
			if ($process == null) {
				$process = new Process;
			} else {
				$pf->id = $process->id;
			}
			$pf->process = $process;
			return $pf;
		}
                
		/**
		* 
		 * Return all the parameters that are inputs
		 * 
		 */
		public function inputs() {
                    $returnset = array();
                       foreach($this->parameters as $param) {
                           if ($param->is_input_field) {
                               $returnset[] = $param;
                           }
                       }
			return $returnset;
		}

		/**
		* 
		 * return the parameter object in this process_form by id, returns null if no association exists
		 * @param id: the parameter id 
		 */
		public function get_parameter_by_id($id) {
			if (isset($this->parameters[$id])) {
				return $this->parameters[$id];
			}
			return null;
		}

		/**
		* 
		 * set the value for a parameter 
		 * @param id: the parameter id 
		 * @param value: the new value for this parameter
		 */
		public function set_parameter_by_id($id, $value) {
			if (isset($this->parameters[$id])) {
				$this->parameters[$id]->value = $value;
			}
			return null;
		}


		/**
		* 
		 * remove the parameter object in this process_form by id, returns null if no association exists
		 * @param process: a process that this form is associated with
		 */
		public function remove_parameter_by_id($id) {
			if (isset($this->parameters[$id])) {
				$this->parameters[$id] = null;
				unset($this->parameters[$id]);
			}
		}

		/**
		* 
		 * Saves any changes that have been made to this process_form object and any of its parameters
		 * @param process: a process that this form is associated with
		 */
		public function save() {
			$queries = array();

			//delete any parameters from process_form table that have been removed
			$existing_ids = implode(',' , array_keys($this->parameters));
			$delete_query = "delete from process_form where process_id = '$this->id' and parameter_id not in ($existing_ids)";
			db_query($delete_query);
			
			//go over each parameter and update as necessary
			foreach($this->parameters as $id => $parameter) {
				$parameter->save();
				$sql = "SELECT * FROM process_form WHERE process_id = '$this->id' and parameter_id = '$id'";
				$rows = db_query_with_column_names($sql);
				if ($rows) {
					$sql = "UPDATE process_form 
						SET value = '$parameter->value', 
						confidence = '$parameter->confidence', 
						data_origin = '$parameter->data_origin'  
						WHERE process_id = '$this->id' and parameter_id = '$id'";
				} else {
					$sql = "INSERT INTO process_form (value, confidence, data_origin, process_id, parameter_id) 
						VALUES( '$parameter->value', 
							'$parameter->confidence', 
							'$parameter->data_origin',  
							'$this->id', 
							'$id') ";
				}
				db_query($sql);
			}
		}

		/**
		* 
		 * Removes this process_form from the DB 
		 * @param process: a process that this form is associated with
		 */
		public function remove() {
			if ($this->id) {
				$delete_query = "delete from process_form where process_id = '$this->id' ;";
				db_query($delete_query);
				unset($this);
			} else {
				throw new Exception('Trying to remove process_form with no process id');
			}
		}

		public function add_parameter($parameter) {
			if (isset($this->parameters[$parameter->id])) {
				throw new Exception('Trying to add parameter that already exists');
			}
			$this->parameters[$parameter->id] = $parameter;
		}

		public function remove_parameter() {
		}

		private function initialize($sql_results) {
			if ($sql_results) {
				$this->id = ($sql_results[0]['process_id']);
				$params = Parameter::get_by_process_id($this->id);
				foreach($params as $p) {
					$this->parameters[$p->id] = $p;
				}
				foreach($sql_results as $row) {
					if (isset($this->parameters[$row['parameter_id']])) {
						$this->parameters[$row['parameter_id']]->value = $row['value'];
						$this->parameters[$row['parameter_id']]->confidence = $row['confidence'];
						$this->parameters[$row['parameter_id']]->data_origin = $row['data_origin'];
					}
				}
				$this->process = Process::get_by_id($this->id);
			} else {
				//throw new Exception("Empty result set in initialize");
			}
				
		}
	}
?>
