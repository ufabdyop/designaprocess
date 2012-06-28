<?php header('Content-Type: application/javascript');?>

//set up all the process objects
var all_processes = new indexer();

<?php
	require_once('../library/pdo.php');
	require_once('../library/json_helper.php');
	require_once('../library/helper_functions.php');
        
        
        $myJsonEncoder = new Services_JSON();

	$rows = db_query("select id from processes");

        foreach($rows as $row) {
            $process_id = $row['id'];
		if (!$process_id) {
			echo "Please specify a process\n";
			die ;
		}
		$process_index = db_query("select * from processes where id = '$process_id'");
		if ($process_index) {
			$process_name = $process_index[0][1] . '_' .$process_index[0][2] . '_' .$process_index[0][3] . '_'.$process_index[0][4] ;
			$process_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $process_name);
		} else {
			continue;
		}
		$sql = ("SELECT id, name, type, unit, is_input_field, process_form.value 
				FROM process_form 
				LEFT JOIN parameter 
				ON process_form.parameter_id = parameter.id 
				WHERE process_form.process_id = '$process_id'");
		$fields = db_query($sql);

                /*
                 * Print out a header for this process
                 * 
                 */
                echo "\n\n// Process Name: $process_name\n";
		echo "var $process_name = new Object();\n";
		echo "$process_name.id = '" . $process_index[0]['id'] . "';\n";
		echo "$process_name.category = '" . $process_index[0]['category'] . "';\n";
		echo "$process_name.process = '" . $process_index[0]['process'] . "';\n";
		echo "$process_name.material = '" . $process_index[0]['material'] . "';\n";
		echo "$process_name.tool = '" . $process_index[0]['tool'] . "';\n";

		$process_index[0]['category'];
                $all_input_fields = array(); 
                $parameters = array(); 
                $all_parameters = array(); 
		foreach($fields as $parameter) {
			$parameter['name']  = preg_replace('/[^a-zA-Z0-9_]/', '_', $parameter['name']); //replace chars that can't be in javascript variables
                        
                        /*
                         * build the javascript line that looks like this: 
                         * Deposition_ALD_Cambridge_Fiji_F200_Al2O3.Recipe = 'Standard';
                         * or this:
                         * Deposition_ALD_Cambridge_Fiji_F200_Al2O3.PumpDownTime = new scalar(0.6, 'Hour');
                         */
                        if ($parameter['value']) {
                            $lhs = "$process_name." . $parameter['name'] ; //left hand side of equation
                            if ($parameter['unit']) {
								//check for scientific notation
								preg_match('/(.*)x10\^(.*)/', $parameter['value'], $matches);
								if ($matches) {
									$value = $matches[1] . ' * Math.exp(10, ' . $matches[2] . ')' ;  
								} else {
									$value = $parameter['value'];
								}
								$value = trim($value);
                                $rhs = "new scalar(" . $value . ", '" . $parameter['unit'] . "')"; //right hand side of equation
                            } else {
                                $rhs = "'" . trim($parameter['value']) . "'"; //right hand side of equation
                            }
							$rhs = "{ id: '" . $parameter['id'] . "', value: " . $rhs . " }";

                            echo "$lhs  =  $rhs;\n";
                        }
                        
                        if ($parameter['is_input_field']) {
                            $key = $parameter['name'];
                            $unit = $parameter['unit'];
                            $all_input_fields[$key] = array('unit' => $unit, 'id' => $parameter['id']);
                        }
			$parameter_name = $parameter['name'];
			if ($parameter_name) {
                            $unit = ( isset($unit) ? $unit : null );
                            $all_parameters[$parameter_name] = array('unit' => $unit, 'id' => $parameter['id']);
			}
		}
                
                $json_string = $myJsonEncoder->encode($all_input_fields);
                echo "$process_name.input_fields = $json_string;\n";

                $json_string = $myJsonEncoder->encode($all_parameters);
                echo "$process_name.parameters = $json_string;\n";
                
                //add equations
                $sql = "select * from equation where process_id = '$process_id'";
                $equations = db_query_with_column_names($sql);
                echo "$process_name.equations = " . $myJsonEncoder->encode($equations) . ";\n";
                
                //get all the parameters that are used in the equation
                $practical_equations = array();
                foreach($equations as $equation) {
                    $variable_regex = '/var_id(\d+)_[A-Za-z][A-Za-z_0-9]*/';
                    $params = preg_match_all($variable_regex, $equation['equation'], $matches);
		    echo "/*\nVariables that show up in equation: \n";
                    echo print_r($matches, true);
		    echo "*/\n";
                    
                    $new_equation =  $equation ['equation'];                    
                    for($i = 0 ; $i < count($matches[0]); $i++ ) {
                        $param_id = $matches[1][$i];
                        $variable = $matches[0][$i];
                        $parameter = get_parameter_for_process($process_id, $param_id);
                        $param_value = (isset( $parameter['value'] ) ? $parameter['value'] : null) ;
                        $is_input = (isset( $parameter['is_input_field'] ) ? $parameter['is_input_field'] : 0) ;
                        echo "//param_value: $param_value\n";
                        if ($param_value) {  
                            $new_equation = str_replace($variable, $param_value, $new_equation);
                            echo "//replacing $variable with $param_value\n";
                        } else {
                            if ($is_input) {
                                $new_equation = str_replace($variable, 'var_id' . $param_id, $new_equation);
                                echo "//replacing $variable with var_id$param_id\n";
                            } else {
                                $new_equation = str_replace($variable, ' null ', $new_equation);
                                echo "//replacing $variable with var_id$param_id\n";
                            }
                        }
                    }
                    $new_equation = preg_replace($variable_regex, 'var_id$1', $new_equation);
                    $equation_name = $equation['name'];
                    $practical_equations[$equation_name] = $new_equation;
                    
                }
                echo "$process_name.practical_equations = " . $myJsonEncoder->encode($practical_equations) . ";\n";

		//add newly created process to indexer
		echo "all_processes.add_process($process_name);\n";

	}
?>
