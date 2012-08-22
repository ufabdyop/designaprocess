//global process: this gets defined when a unique process is found based on process, tool, material
var the_process;

function field(short_name, description, value) {
    this.name = short_name;
    this.description = description;
    this.value = value;
}

function scalar(value, unit) {
    this.value = value;
    this.unit = unit;
}

function calculation(name) {
    this.name = name;
    this.calculate = function () {
    }
}

function indexer() {
    this.all_processes = new Array();
    this.processes_by_category  = new Array();
    this.processes_by_general_process  = new Array();
    this.processes_by_tool  = new Array();
    this.processes_by_material  = new Array();
    this.add_process = function(process_object) {
        this.all_processes.push(process_object);
    }
    this.get_process = function(filter) {
        //filter should be indexed like so: {tool: "toolname", category: "categoryname", general_process: "gp", material: "material" }
        var return_processes = new Array();
        for (var i = 0; i < this.all_processes.length; i++) {
        //for (var process in this.all_processes) {
            for (var key in filter) {
                var return_this = true;
                if (this.all_processes[i][key] == filter[key]) {
                    continue;
                } else {
                    return_this = false;
                    break;
                }
            }
            if (return_this) {
                return_processes.push(this.all_processes[i]);
            }
        }
        return return_processes;
    }
    this.get_process_by_id = function(id) {
        for (var i = 0; i < this.all_processes.length; i++) {
            if (this.all_processes[i]['id'] == id) {
                return this.all_processes[i];
            }
        }
        return null;
    }
    this.get_processes_by_search_term = function(filter) {
        var return_processes = new Array();

        for (var i = 0; i < this.all_processes.length; i++) {
            if (this.all_processes[i]['long_name'].match(new RegExp(filter, 'i'))) {
                return_processes.push(this.all_processes[i]);
            }
        }
        return return_processes;
        
    }
}

function push_if_not_in(array, element) {
    if (typeof (array[element]) === "undefined") {
        array[element] = true;
    }
}
function remove_children(element) {
    if (element.hasChildNodes()) {
        while (element.childNodes.length >= 1) {
            element.removeChild( element.firstChild);
        }
    }
}
function set_select_options(id, values) {
    var option ;
    var element = document.getElementById(id);
    var selected_value = element.value;
    var candidate_count = 0;
    remove_children(element);

    //create empty option
    /*
    option = document.createElement('option');
    option.setAttribute('value', "");
    option.appendChild(document.createTextNode(""));
    element.appendChild(option);
    */

    for (var val in values ) {
        candidate_count++;
    }

    if (candidate_count > 1) {
        option = document.createElement('option');
        option.setAttribute('value', '');
        option.setAttribute('selected', true);
        option.appendChild(document.createTextNode('Please Select An Option'));
        element.appendChild(option);
    }
    for (var val in values ) {
        option = document.createElement('option');
        option.setAttribute('value', val);
        if (val == selected_value) {
            option.setAttribute('selected', true);
        }
        option.appendChild(document.createTextNode(val));
        element.appendChild(option);
    }
}

function narrow_processes(callback) {
    var process = document.getElementById("process").value;
    var category = document.getElementById("category").value;
    var tool = document.getElementById("tool").value;
    var material = document.getElementById("material").value;

    var query_string = new Object();
    if (process != "") {
        query_string.process = process;
    }
    if (category != "") {
        query_string.category = category;
    }
    if (tool != "") {
        query_string.tool = tool;
    }
    if (material != "") {
        query_string.material = material;
    }

    var processes = new Object();
    var categories = new Object();
    var tools = new Object();
    var materials = new Object();

    var candidate_processes = all_processes.get_process(query_string);

    for (var i = 0 ; i < candidate_processes.length; i++) {
        push_if_not_in(processes, candidate_processes[i].process);
        push_if_not_in(categories, candidate_processes[i].category);
        push_if_not_in(tools, candidate_processes[i].tool);
        push_if_not_in(materials, candidate_processes[i].material);
    }

    set_select_options('process', processes) ;
    set_select_options('category', categories) ;
    set_select_options('tool', tools) ;
    set_select_options('material', materials) ;

    if (callback) {
        
    } else {
        callback = create_process_form;
    }
    if (candidate_processes.length == 1) {
        the_process = candidate_processes[0];
        callback(candidate_processes[0]);
    }
}


function create_process_form(process) {
    var element;
    //for(var i =0 ; i < process.input_fields.length; i++ ) {
    for(var i in process.input_fields ) {
        var input = i;
        var unit = process.input_fields[i];
        element = document.createElement('label');
        element.appendChild(document.createTextNode(input));
        document.getElementById('input').appendChild(element);

        element = document.createElement('input');
        element.setAttribute('id', input);
        document.getElementById('input').appendChild(element);

        element = document.createElement('label');
        element.setAttribute('id', input + '_unit');
        element.appendChild(document.createTextNode(unit));
        document.getElementById('input').appendChild(element);

        element = document.createElement('br');
        element.appendChild(document.createTextNode(input));
        document.getElementById('input').appendChild(element);
    }
    element = document.createElement('button');
    element.onclick= run_process;
    element.appendChild(document.createTextNode('calculate'));
    document.getElementById('input').appendChild(element);
}

function add_to_outputs(name, scalar_result ) {
        var element = document.createElement('label');
        element.appendChild(document.createTextNode(name));
        document.getElementById('output').appendChild(element);

        element = document.createElement('input');
        element.setAttribute('id', name);
        element.setAttribute('value', scalar_result.value);
        document.getElementById('output').appendChild(element);

        element = document.createElement('label');
        element.setAttribute('id', name + '_unit');
        element.appendChild(document.createTextNode(scalar_result.unit));
        document.getElementById('output').appendChild(element);

        element = document.createElement('br');
        document.getElementById('output').appendChild(element);
}

function run_process() {
    //set the input fields
    //for(var i =0 ; i < the_process.input_fields.length; i++ ) {
    for(var i in the_process.input_fields ) {
        var input = i;
        the_process[input] = document.getElementById(input).value;
    }
    for(var i =0 ; i < the_process.output_methods.length; i++ ) {
        var output_method = the_process.output_methods[i];
        var eval_string = 'var result = the_process.' + output_method + '();';
        eval(eval_string);
        add_to_outputs(output_method, result);
    }
}

function evaluate_equation(process, equation, inputs) {
    var practical_equation = process.practical_equations[equation];
    var msg = '';
    var final_equation = practical_equation;
    for ( var id in inputs ) {
        msg += "Replacing regex /var_id" + id + '/ with ' + inputs[id] + "\n";
        var final_equation = final_equation.replace(new RegExp('var_id' + id, 'g') , inputs[id]);
        msg += "Result: " + final_equation + "\n";
    }
    
    if (final_equation.match( 'var_id' + '\\d+' )) {
        throw ("Missing input variable!");
    } else {
        return final_equation;
    }

}

function run_calculation(process, equation, inputs) {
        try {
	    var final_equation = evaluate_equation(process, equation, inputs);
            var calculation_result = calculator.parse(final_equation);
            return calculation_result;
        } catch (error) {
            throw ("Error parsing equation: " + final_equation);
        }
}

function equation_to_human_name(equation) {
    return equation.replace(/var_id\d+_/g,'');
    
}
