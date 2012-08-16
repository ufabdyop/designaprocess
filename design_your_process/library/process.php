<?php
 require_once('helper_functions.php');
	class Process {
		var $id = null;
		var $category = null;
		var $process = null;
		var $tool = null;
		var $material = null;
		
		/**
		* Get a process by its ID in the DB
		* @parameters: $id, the id of the process
		* @return: Process object
		*/
		public static function get_by_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}
			$sql = "SELECT * FROM processes where id = $id";
			$results = db_query_with_column_names($sql);
			$param = new Process;
			$param->initialize($results);
			return $param;
		}

		/**
		* 
		 * Creates a new Process in the database
		 * @return int, the id of the process that was saved 
		 */
		public static function create($category = '', $process = '', $tool = '', $material = '' ) {
			db_query("INSERT INTO processes (category, process, tool, material) VALUES ('$category','$process','$tool','$material');");
			$id = last_insert_id();
			return $id;
		}

		public function save() {
		}

		public function remove() {
			if ($this->id) {
				db_query("DELETE FROM processes WHERE id = '$this->id'"); 
			} else {
				throw new Exception("deleting non-object process");
			}
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

		public function equals( self $other) {
			foreach (get_object_vars($this) as $attribute => $val) {
				if ($this->$attribute != $other->$attribute) {
					return false;
				}
			}
			return true;
		}
	}
?>
