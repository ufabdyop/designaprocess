<?php

/*
 * Wrapper for CodeIgniter's form_helper
 * Example:
 * $form = new Form();
 * $form->input('username', 'johndoe');
 * $form->html();
 * 
 */
require_once ('form_helper.php');
class Form {
    private $html = "";
    private $include_label = "";
    function __construct() {
        
    }
	function add_html($string) {
		$this->html .= $string;
	}
	function html() {
		return $this->html;
	}
        
        function add_label_or_not($data) {
            if (isset($data['label'])) {
                $label = $data['label'];
                $html = '<li id="li_1">
                            <label class="description"';
                            
                if (isset($data['id'])) {
                    $id = $data['id'];
                    $html .= ' for="' . $id . '"';
                }
                $html .= '>' . $label . '</label>
                <div>';
                $this->html .= $html;
                return true;
            }
            return false;
        }
        function end_label_or_not($data) {
            if (isset($data['label'])) {
                $this->html .= '</div>
                    </li>';
            }
        }
        
        function radio_list($data) {
            $this->add_label_or_not($data);
            if (isset($data['radios'])) {
                foreach($data['radios'] as $radio) {
                    $radio['name'] = $data['name'];
                    $this->radio($radio);
                    $this->label($radio['label'], $radio['id'], array('class' => 'choice'));
                }
            }
            $this->end_label_or_not($data);
        }

	function open($action = '', $attributes = '', $hidden = array()) {
		$this->add_html( form_open($action , $attributes , $hidden ) );
	}
	function hidden($name, $value = '', $recursing = FALSE) {
		$this->add_html( form_hidden($name, $value , $recursing ) );
	}
	function input($data = '', $value = '', $extra = '') {
            $label = $this->add_label_or_not($data);
            $this->add_html( form_input($data , $value , $extra ) );
            $this->end_label_or_not($data) ;
	}
	function password($data = '', $value = '', $extra = '') {
		$this->add_html( form_password($data , $value , $extra ) );
	}
	function upload($data = '', $value = '', $extra = '') {
		$this->add_html( form_upload($data , $value , $extra ) );
	}
	function textarea($data = '', $value = '', $extra = '') {
            $this->add_label_or_not($data);
		$this->add_html( form_textarea($data , $value , $extra ) );
            $this->end_label_or_not($data);
	}
	function multiselect($name = '', $options = array(), $selected = array(), $extra = '') {
		$this->add_html( form_multiselect($name , $options , $selected , $extra ) );
	}
	function dropdown($name = '', $options = array(), $selected = array(), $extra = '') {
		$this->add_html( form_dropdown($name , $options , $selected , $extra ) );
	}
	function checkbox($data = '', $value = '', $checked = FALSE, $extra = '') {
		$this->add_html( form_checkbox($data , $value , $checked , $extra ) );
	}
	function radio($data = '', $value = '', $checked = FALSE, $extra = '') {
		$this->add_html( form_radio($data , $value , $checked , $extra ) );
	}
	function submit($data = '', $value = '', $extra = '') {
		$this->add_html( form_submit($data , $value , $extra ) );
	}
	function reset($data = '', $value = '', $extra = '') {
		$this->add_html( form_reset($data , $value , $extra ) );
	}
	function button($data = '', $content = '', $extra = '') {
		$this->add_html( form_button($data , $content , $extra ) );
	}
	function label($label_text = '', $id = '', $attributes = array()) {
		$this->add_html( form_label($label_text , $id , $attributes ) );
	}
	function fieldset($legend_text = '', $attributes = array()) {
		$this->add_html( form_fieldset($legend_text , $attributes ) );
	}
	function fieldset_close($extra = '') {
		$this->add_html( form_fieldset_close($extra ) );
	}
	function close($extra = '') {
		$this->add_html( form_close($extra ) );
	}
	function prep($str = '', $field_name = '') {
		$this->add_html( form_prep($str , $field_name ) );
	}
	function error($field = '', $prefix = '', $suffix = '') {
		$this->add_html( form_error($field , $prefix , $suffix ) );
	}
}

?>
