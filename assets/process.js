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
    remove_children(element);

    //create empty option
    /*
    option = document.createElement('option');
    option.setAttribute('value', "");
    option.appendChild(document.createTextNode(""));
    element.appendChild(option);
    */

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
function narrow_processes() {
    var process = document.getElementById("process").value;
    var general_process = document.getElementById("general_process").value;
    var tool = document.getElementById("tool").value;
    var material = document.getElementById("material").value;

    var query_string = new Object();
    if (process != "") {
        query_string.process = process;
    }
    if (general_process != "") {
        query_string.general_process = general_process;
    }
    if (tool != "") {
        query_string.tool = tool;
    }
    if (material != "") {
        query_string.material = material;
    }

    var processes = new Object();
    var general_processes = new Object();
    var tools = new Object();
    var materials = new Object();

    var candidate_processes = all_processes.get_process(query_string);

    for (var i = 0 ; i < candidate_processes.length; i++) {
        push_if_not_in(processes, candidate_processes[i].process);
        push_if_not_in(general_processes, candidate_processes[i].general_process);
        push_if_not_in(tools, candidate_processes[i].tool);
        push_if_not_in(materials, candidate_processes[i].material);
    }

    set_select_options('process', processes) ;
    set_select_options('general_process', general_processes) ;
    set_select_options('tool', tools) ;
    set_select_options('material', materials) ;

    if (candidate_processes.length == 1) {
        the_process = candidate_processes[0];
        create_process_form(candidate_processes[0]);
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

//set up all the process objects
var all_processes = new indexer();

//deposition
//denton - aluminum
var denton_discovery_18_aluminum = new Object();
denton_discovery_18_aluminum.process = 'Deposition';
denton_discovery_18_aluminum.general_process = 'Sputter';
denton_discovery_18_aluminum.tool = 'Denton Discovery 18';
denton_discovery_18_aluminum.material = 'Al';
denton_discovery_18_aluminum.head_number = '1';
denton_discovery_18_aluminum.base_pressure = new scalar(1 * ( 10 ^ -6), 'Torr');
denton_discovery_18_aluminum.pump_down_time  = new scalar(0.6, 'Hour');
denton_discovery_18_aluminum.pre_sputter_time  = new scalar(2, 'Minute');
denton_discovery_18_aluminum.power = new scalar(50, 'Watt');
denton_discovery_18_aluminum.ar_pressure = new scalar(3, 'mT');
denton_discovery_18_aluminum.ar_flow = new scalar(50, 'sccm');
denton_discovery_18_aluminum.film_resistivity = new scalar(3.6, 'uOhm-cm');
denton_discovery_18_aluminum.literature_bulk_resistivity = new scalar(2.8, 'uOhm-cm');
denton_discovery_18_aluminum.sputter_rate = new scalar(16.67, 'nm/min');
denton_discovery_18_aluminum.estimated_runtime = function() {
    return new scalar (this.desired_film_thickness / this.sputter_rate.value, 'min') ;
}
denton_discovery_18_aluminum.estimated_sheet_resistance = function() {
    return new scalar (this.film_resistivity.value / this.desired_film_thickness, 'uOhm/sq') ;
}
denton_discovery_18_aluminum.input_fields = {desired_film_thickness: 'nm'};
denton_discovery_18_aluminum.output_methods = ['estimated_sheet_resistance', 'estimated_runtime'];

//denton - chromium
denton_discovery_18_chromium = Object.create(denton_discovery_18_aluminum);
denton_discovery_18_chromium.material = 'Cr';
denton_discovery_18_chromium.head_number = 3;
denton_discovery_18_chromium.film_resistivity = new scalar(7.45, 'uOhm-cm');
denton_discovery_18_chromium.literature_bulk_resistivity = new scalar(2.8, 'uOhm-cm');
denton_discovery_18_chromium.sputter_rate = new scalar(8.48, 'nm/min');

//Etching
//oxford 100 - rie - sio2 5%
var oxford_100_RIE_SiO2_5_percent = new Object();

oxford_100_RIE_SiO2_5_percent.process = 'Etching';
oxford_100_RIE_SiO2_5_percent.general_process = 'RIE';
oxford_100_RIE_SiO2_5_percent.tool = 'Oxford 100 ICP';
oxford_100_RIE_SiO2_5_percent.material = 'SiO2 5% Area';
oxford_100_RIE_SiO2_5_percent.recipe = 'BB CF4/O2 10mT';
oxford_100_RIE_SiO2_5_percent.etch_pressure = new scalar( 10, 'mT');
oxford_100_RIE_SiO2_5_percent.gas1 = 'O2';
oxford_100_RIE_SiO2_5_percent.gas1_flow = new scalar( 05, 'sccm');
oxford_100_RIE_SiO2_5_percent.gas_1_rf_power = new scalar( 100, 'W');
oxford_100_RIE_SiO2_5_percent.gas2 = 'CF4';
oxford_100_RIE_SiO2_5_percent.gas2_flow = new scalar(50 , 'sccm');
oxford_100_RIE_SiO2_5_percent.gas_2_rf_power = new scalar(100 , 'W');
oxford_100_RIE_SiO2_5_percent.number_of_etch_cycles = null;
oxford_100_RIE_SiO2_5_percent.temperature = new scalar( 26, 'degrees C');
oxford_100_RIE_SiO2_5_percent.icp_power = new scalar( 500, 'W');
oxford_100_RIE_SiO2_5_percent.etch_rate = new scalar( 0.1139, 'um/min');
oxford_100_RIE_SiO2_5_percent.uniformity = new scalar( 5.78, '+/- %');
oxford_100_RIE_SiO2_5_percent.desired_etch_depth = null;
oxford_100_RIE_SiO2_5_percent.estimated_run_time = function() {
	return new scalar(this.desired_etch_depth / this.etch_rate.value, 'min') ;
}
oxford_100_RIE_SiO2_5_percent.input_fields = {desired_etch_depth: 'nm'};
oxford_100_RIE_SiO2_5_percent.output_methods = ['estimated_run_time'];

//add to list of all processes
all_processes.add_process(denton_discovery_18_aluminum);
all_processes.add_process(denton_discovery_18_chromium);
all_processes.add_process(oxford_100_RIE_SiO2_5_percent);

