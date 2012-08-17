<?php
 require_once('helper_functions.php');
	class Equation {
		var $id = null;
		var $process_id;
		var $name;
		var $equation;
		var $unit;
		
		/**
		* Get an array of equations that correspond to a specific process
		* @parameters: $id, the id of the process
		* @return: array of Equation objects
		*/
		public static function get_by_process_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}
			$sql = "SELECT * FROM equation where process_id = '$id'";
			$results = db_query_with_column_names($sql);
			$params = array();
			foreach($results as $row) {
				$param = new Equation;
				$param->initialize(array($row));
				$params[] = $param;
			}
			return $params;
		}
		
		
		/**
		* Get a parameter by its ID in the DB
		* @parameters: $id, the id of the parameter
		* @return: Parameter object
		*/
		public static function get_by_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}
			$sql = "SELECT * FROM equation where id = $id";
			$results = db_query_with_column_names($sql);
			$param = new Equation;
			$param->initialize($results);
			return $param;
		}

		public static function create($name = '', $equation = '', $unit = '', $process = '') {
		}

		public function save() {
		}

		public function remove() {
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
