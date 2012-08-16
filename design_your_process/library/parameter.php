<?php
 require_once('helper_functions.php');
	class Parameter {
		var $id = null;
		var $name;
		var $value;
		var $type;
		var $unit;
		var $active;
		var $updated_at;
		var $is_input_field;
		var $is_process_parameter;
		var $is_measured_result;
		var $confidence;
		var $data_origin;
		
		/**
		* Get an array of parameters that correspond to a specific process
		* @parameters: $id, the id of the process
		* @return: array of Parameter objects
		*/
		public static function get_by_process_id($id) {
			if (!is_numeric($id)) {
				throw new Exception("$id is not a number");
			}
			$sql = "SELECT * FROM parameter where id in (SELECT parameter_id from process_form where process_id = $id)";
			$results = db_query_with_column_names($sql);
			$params = array();
			foreach($results as $row) {
				$param = new Parameter;
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
			$sql = "SELECT * FROM parameter where id = $id";
			$results = db_query_with_column_names($sql);
			$param = new Parameter;
			$param->initialize($results);
			return $param;
		}

		public static function create($name = '', $type = '', $unit = '', $active = 1, $is_input_field = 0, $is_process_parameter = 1, $is_measured_result = 0) {
			db_query("INSERT INTO parameter (name, type, unit, active, is_input_field, is_process_parameter, is_measured_result) VALUES ('$name', '$type', '$unit', '$active', '$is_input_field', '$is_process_parameter', '$is_measured_result') ");
			$id = last_insert_id();
			return $id;
		}

		public function save() {
		}

		public function remove() {
			if ($this->id) {
				db_query("DELETE FROM parameter WHERE id = '$this->id'");
			}else {
				throw new Exception('Could not delete parameter');
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
	}
?>
