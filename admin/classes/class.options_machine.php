<?php 
/**
 * SMOF Options Machine Class
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.0.0
 * @author      Syamil MJ
 */
class Options_Machine {
	/**
	 * PHP5 contructor
	 *
	 * @since 1.0.0
	 */
	function __construct($options) {
		$return = $this->optionsframework_machine($options);
		$this->Inputs = $return[0];
		$this->Menu = $return[1];
		$this->Defaults = $return[2];
		$this->Groups = $return[3];
	}
	/** 
	 * Sanitize option
	 *
	 * Sanitize & returns default values if don't exist
	 * 
	 * Notes:
	 *	- For further uses, you can check for the $value['type'] and performs
	 *	  more speficic sanitization on the option
	 *	- The ultimate objective of this function is to prevent the "undefined index"
	 *	  errors some authors are having due to malformed options array
	 */
	public static function sanitize_option( $value ) {
		$defaults = array(
			"name" 		=> "",
			"desc" 		=> "",
			"id" 		=> "",
			"std" 		=> "",
			"mod"		=> "",
			"type" 		=> "",
			"group" 	=> ""
		);
		$value = wp_parse_args( $value, $defaults );
		return $value;
	}
	/**
	 * Process options data and build option fields
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function optionsframework_machine($options) {
		global $smof_output;
		$smof_data = of_get_options();
		$data = $smof_data;
		$defaults = array();
		$counter = 0;
		$menu = '';
		$output = '';
		$groups_ext = '';
		
		do_action('optionsframework_machine_before', array(
				'options'	=> $options,
				'smof_data'	=> $smof_data,
			));
		$output .= $smof_output;
		foreach ($options as $value) {
			// sanitize option
			$value = self::sanitize_option($value);
			$counter++;
			$val = '';
			//create array of defaults		
			if ($value['type'] == 'multicheck'){
				if (is_array($value['std'])){
					foreach($value['std'] as $i=>$key){
						$defaults[$value['id']][$key] = true;
					}
				} else {
						$defaults[$value['id']][$value['std']] = true;
				}
			} else {
				if (isset($value['id'])) $defaults[$value['id']] = $value['std'];
			}
			/* condition start */
			if(!empty($smof_data) || !empty($data)){
			//Start Heading
			if ( $value['type'] != "heading" )
			{
				$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
				//hide items in checkbox group
				$fold='';
				if (array_key_exists("fold",$value)) {
					if (isset($smof_data[$value['fold']]) && $smof_data[$value['fold']]) {
						$fold="f_".$value['fold']." ";
					} else {
						$fold="f_".$value['fold']." temphide ";
					}
				}
				$output .= '<div id="section-'.$value['id'].'" class="'.$fold.'section section-'.$value['type'].' group-'.$value['group'].' '. $class .'">'."\n";
				//only show header if 'name' value exists
				if($value['name']) $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
				$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
			}
			//End Heading
			$array =array('info','backup','transfer','group');
			if (!in_array($value['type'], $array)) {
				if (!isset($smof_data[$value['id']]) && $value['type'] != "heading" ) continue;
			}
			
			
			//switch statement to handle various options type
			switch ( $value['type'] ) {
				//text input
				case 'text':
					$t_value = '';
					$t_value = stripslashes($smof_data[$value['id']]);
					$mini ='';
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					$output .= '<input class="of-input '.$mini.'" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $t_value .'" />';
				break;
				//select option
				case 'select':
					$mini ='';
					$values = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					$output .= '<div class="select_wrapper ' . $mini . '">';
					$output .= '<select class="select of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
					foreach ($value['options'] as $select_ID => $option) {			
						$output .= '<option id="' . $select_ID . '" value="' . $select_ID . '" ' . selected($values, $select_ID, false) . ' />'.$option.'</option>';	 
					 } 
					$output .= '</select></div>';
				break;
				//textarea option
				case 'textarea':	
					$cols = '8';
					$ta_value = '';
					if(isset($value['options'])){
							$ta_options = $value['options'];
							if(isset($ta_options['cols'])){
							$cols = $ta_options['cols'];
							} 
						}
						$ta_value = stripslashes($smof_data[$value['id']]);
						$output .= '<textarea class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';		
				break;
				//radiobox option
				case "radio":
					$checked = (isset($smof_data[$value['id']])) ? checked($smof_data[$value['id']], $option, false) : '';
					 foreach($value['options'] as $option=>$name) {
						$output .= '<input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($smof_data[$value['id']], $option, false) . ' /><label class="radio">'.$name.'</label><br/>';				
					}
				break;
				//checkbox option
				case 'checkbox':
					if (!isset($smof_data[$value['id']])) {
						$smof_data[$value['id']] = 0;
					}
					$fold = '';
					if (array_key_exists("folds",$value)) $fold="fld ";
					$output .= '<input type="hidden" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
					$output .= '<input type="checkbox" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($smof_data[$value['id']], 1, false) .' />';
				break;
				//multiple checkbox option
				case 'multicheck': 
					(isset($smof_data[$value['id']]))? $multi_stored = $smof_data[$value['id']] : $multi_stored="";
					foreach ($value['options'] as $key => $option) {
						if (!isset($multi_stored[$key])) {$multi_stored[$key] = '';}
						$of_key_string = $value['id'] . '_' . $key;
						$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'['.$key.']'.'" id="'. $of_key_string .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label class="multicheck" for="'. $of_key_string .'">'. $option .'</label><br />';								
					}
				break;
				// Color picker
				case "color":
					$default_color = '';
					if ( isset($value['std']) ) {
						if ( $smof_data[$value['id']] !=  $value['std'] )
							$default_color = ' data-default-color="' .$value['std'] . '" ';
					}
					$output .= '<input name="' . $value['id'] . '" id="' . $value['id'] . '" class="of-color"  type="text" value="' . $smof_data[$value['id']] . '"' . $default_color .' />';
				break;
				//typography option	
				case 'typography':
                    /* Google Fonts list */
                    $fonts = require dirname( __FILE__ ) . '/google_fonts_list.php';
					$typography_stored = isset($smof_data[$value['id']]) ? $smof_data[$value['id']] : $value['std'];
/*					if ( isset($smof_data[$value['id']]['face']) ) {
						var_dump($smof_data[$value['id']]);
					}
					else {
						var_dump($smof_data[$value['id']]);
						$curr_face = $smof_data[$value['id']];
						set_theme_mod($value['id'], array("face"=>$curr_face,"style"=>"normal","weight"=>"400"));
					}*/
					/* Font Face */
					if( ( isset($typography_stored['face']) ? $typography_stored['face'] : '') ) {
						$output .= '<div class="select_wrapper typography-face" original-title="Font family">';
						$output .= '<select class="of-typography of-typography-face select" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';
						$faces = $fonts;
						foreach ($faces as $i=>$face) {
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
						}
						$output .= '</select></div>';
					}
					/* Font Style */
					if( ( isset($typography_stored['style']) ? $typography_stored['style'] : '') ) {
						$output .= '<div class="select_wrapper typography-style" original-title="Font style">';
						$output .= '<select class="of-typography of-typography-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
						$styles = array(
							'normal' => 'Normal',
							'italic' => 'Italic'
						);
						foreach ($styles as $i=>$style){
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
						}
						$output .= '</select></div>';
					}
					/* Font Weight */
					if( ( isset($typography_stored['weight']) ? $typography_stored['weight'] : '') ) {
						$output .= '<div class="select_wrapper typography-weight" original-title="Font weight">';
						$output .= '<select class="of-typography of-typography-weight select" name="'.$value['id'].'[weight]" id="'. $value['id'].'_weight">';
							for ($i = 1; $i < 9; $i++){ 
								$test = $i*100;
								$output .= '<option value="'. $test .'" ' . selected($typography_stored['weight'], $test, false) . '>'. $test .'</option>'; 
								}
						$output .= '</select></div>';
					}
				break;
				//border option
				case 'border':
					/* Border Width */
					$border_stored = $smof_data[$value['id']];
					$output .= '<div class="select_wrapper border-width">';
					$output .= '<select class="of-border of-border-width select" name="'.$value['id'].'[width]" id="'. $value['id'].'_width">';
						for ($i = 0; $i < 21; $i++){ 
						$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';				 }
					$output .= '</select></div>';
					/* Border Style */
					$output .= '<div class="select_wrapper border-style">';
					$output .= '<select class="of-border of-border-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
					$styles = array('none'=>'None',
									'solid'=>'Solid',
									'dashed'=>'Dashed',
									'dotted'=>'Dotted');
					foreach ($styles as $i=>$style){
						$output .= '<option value="'. $i .'" ' . selected($border_stored['style'], $i, false) . '>'. $style .'</option>';		
					}
					$output .= '</select></div>';
					/* Border Color */		
					$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color: '.$border_stored['color'].'"></div></div>';
					$output .= '<input class="of-color of-border of-border-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $border_stored['color'] .'" />';
				break;
				//images checkbox - use image as checkboxes
				case 'images':
					$i = 0;
					$select_value = (isset($smof_data[$value['id']])) ? $smof_data[$value['id']] : '';
					foreach ($value['options'] as $key => $option) 
					{ 
					$i++;
						$checked = '';
						$selected = '';
						if(NULL!=checked($select_value, $key, false)) {
							$checked = checked($select_value, $key, false);
							$selected = 'of-radio-img-selected';  
						}
						$output .= '<span>';
						$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'.$value['id'].'" '.$checked.' />';
						$output .= '<div class="of-radio-img-label">'. $key .'</div>';
						$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
						$output .= '</span>';
					}
				break;
				//info (for small intro box etc)
				case "info":
					$info_text = $value['std'];
					$output .= '<div class="of-info">'.$info_text.'</div>';
				break;
				//display a single image
				case "image":
					$src = $value['std'];
					$output .= '<img src="'.$src.'">';
				break;
				//tab heading
				case 'heading':
					if($counter >= 2){
						$output .= '</div>'."\n";
					}
					//custom icon
					$icon = '';
					if(isset($value['icon'])){
						$icon = '<i class="'.$value['icon'] .'"></i>';
					}
					$add_responsive_classes = '';
					if(isset($value['responsive'])){
						$add_responsive_classes = pbtheme_responsive_classes($value['responsive']);
					}
					$add_element = '';
					if(isset($value['element'])){
						$add_element = 'data-element="'.$value['element'].'"';
					}
					$header_class = str_replace(' ','',strtolower($value['name']));
					$jquery_click_hook = str_replace(' ', '', strtolower($value['name']) );
					$jquery_click_hook = "of-option-" . $jquery_click_hook;
					$menu .= '<li class="'.$header_class.' '.$add_responsive_classes.'" data-group="'.$value['group'].'"><a title="'. $value['name'] .'" href="#'.  $jquery_click_hook  .'">'. $icon .' '.  $value['name'] .'</a></li>';
					$output .= '<div class="group" data-group="'.$value['group'].'"'.$add_element.' id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
				break;
				//drag & drop slide manager
				case 'slider':
					$output .= '<div class="slider"><ul id="'.$value['id'].'">';
					$slides = $smof_data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button slide_add_button">Add New Slide</a></div>';
				break;
				//drag & drop block manager
				case 'sorter':
					// Make sure to get list of all the default blocks first
					$all_blocks = $value['std'];
					$temp = array(); // holds default blocks
					$temp2 = array(); // holds saved blocks
					foreach($all_blocks as $blocks) {
						$temp = array_merge($temp, $blocks);
					}
					$sortlists = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					foreach( $sortlists as $sortlist ) {
						$temp2 = array_merge($temp2, $sortlist);
					}
					// now let's compare if we have anything missing
					foreach($temp as $k => $v) {
						if(!array_key_exists($k, $temp2)) {
							$sortlists['disabled'][$k] = $v;
						}
					}
					// now check if saved blocks has blocks not registered under default blocks
					foreach( $sortlists as $key => $sortlist ) {
					foreach($sortlist as $k => $v) {
						if(!array_key_exists($k, $temp)) {
							unset($sortlist[$k]);
						}
					}
					$sortlists[$key] = $sortlist;
					}
					// assuming all sync'ed, now get the correct naming for each block
					foreach( $sortlists as $key => $sortlist ) {
						foreach($sortlist as $k => $v) {
							$sortlist[$k] = $temp[$k];
						}
					$sortlists[$key] = $sortlist;
					}
					$output .= '<div id="'.$value['id'].'" class="sorter">';
					if ($sortlists) {
					foreach ($sortlists as $group=>$sortlist) {
						$output .= '<ul id="'.$value['id'].'_'.$group.'" class="sortlist_'.$value['id'].'">';
						$output .= '<h3>'.$group.'</h3>';
						foreach ($sortlist as $key => $list) {
							$output .= '<input class="sorter-placebo" type="hidden" name="'.$value['id'].'['.$group.'][placebo]" value="placebo">';
							if ($key != "placebo") {
								$output .= '<li id="'.$key.'" class="sortee">';
								$output .= '<input class="position" type="hidden" name="'.$value['id'].'['.$group.']['.$key.']" value="'.$list.'">';
								$output .= $list;
								$output .= '</li>';
							}
						}
						$output .= '</ul>';
					}
				 	}
					$output .= '</div>';
				break;
				//background images option
				case 'tiles':
					$i = 0;
					$select_value = isset($smof_data[$value['id']]) && !empty($smof_data[$value['id']]) ? $smof_data[$value['id']] : '';
					if (is_array($value['options'])) {
						foreach ($value['options'] as $key => $option) { 
						$i++;
							$checked = '';
							$selected = '';
							if(NULL!=checked($select_value, $option, false)) {
								$checked = checked($select_value, $option, false);
								$selected = 'of-radio-tile-selected';  
							}
							$output .= '<span>';
							$output .= '<input type="radio" id="of-radio-tile-' . $value['id'] . $i . '" class="checkbox of-radio-tile-radio" value="'.$option.'" name="'.$value['id'].'" '.$checked.' />';
							$output .= '<div class="of-radio-tile-img '. $selected .'" style="background: url('.$option.')" onClick="document.getElementById(\'of-radio-tile-'. $value['id'] . $i.'\').checked = true;"></div>';
							$output .= '</span>';				
						}
					}
				break;
				//backup and restore options data
				case 'backup':
					$instructions = $value['desc'];
					$backup = of_get_options(BACKUPS);
					$init = of_get_options('smof_init');
					if(!isset($backup['backup_log'])) {
						$log = 'No backups yet';
					} else {
						$log = $backup['backup_log'];
					}
					$output .= '<div class="backup-box">';
					$output .= '<div class="instructions">'.$instructions."\n";
					$output .= '<p><strong>'. __('Last Backup : ').'<span class="backup-log">'.$log.'</span></strong></p></div>'."\n";
					$output .= '<a href="#" id="of_backup_button" class="button" title="Backup Options">Backup Options</a>';
					$output .= '<a href="#" id="of_restore_button" class="button" title="Restore Options">Restore Options</a>';
					$output .= '</div>';
				break;
				//export or import data between different installs
				case 'transfer':
					$instructions = $value['desc'];
					$output .= '<textarea id="export_data" rows="8">'.base64_encode(serialize($smof_data)) /* 100% safe - ignore theme check nag */ .'</textarea>'."\n";
					$output .= '<a href="#" id="of_import_button" class="button" title="Restore Options">Import Options</a>';
				break;
				// google font field
				case 'select_google_font':
					$output .= '<div class="select_wrapper">';
					$output .= '<select class="select of-input google_font_select" name="'.$value['id'].'" id="'. $value['id'] .'">';
					foreach ($value['options'] as $select_key => $option) {
						$output .= '<option value="'.$select_key.'" ' . selected((isset($smof_data[$value['id']]))? $smof_data[$value['id']] : "", $option, false) . ' />'.$option.'</option>';
					} 
					$output .= '</select></div>';
					if(isset($value['preview']['text'])){
						$g_text = $value['preview']['text'];
					} else {
						$g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
					}
					if(isset($value['preview']['size'])) {
						$g_size = 'style="font-size: '. $value['preview']['size'] .';"';
					} else { 
						$g_size = '';
					}
					$output .= '<p class="'.$value['id'].'_ggf_previewer google_font_preview" '. $g_size .'>'. $g_text .'</p>';
				break;
				//JQuery UI Slider
				case 'sliderui':
					$s_val = $s_min = $s_max = $s_step = $s_edit = '';//no errors, please
					$s_val  = stripslashes($smof_data[$value['id']]);
					if(!isset($value['min'])){ $s_min  = '0'; }else{ $s_min = $value['min']; }
					if(!isset($value['max'])){ $s_max  = $s_min + 1; }else{ $s_max = $value['max']; }
					if(!isset($value['step'])){ $s_step  = '1'; }else{ $s_step = $value['step']; }
					if(isset($value['edit']) && $value['edit'] !== true ){ 
						$s_edit  = ' readonly="readonly"'; 
					}
					else
					{
						$s_edit  = '';
					}
					if ($s_val == '') $s_val = $s_min;
					//values
					$s_data = 'data-id="'.$value['id'].'" data-val="'.$s_val.'" data-min="'.$s_min.'" data-max="'.$s_max.'" data-step="'.$s_step.'"';
					//html output
					$output .= '<input type="text" name="'.$value['id'].'" id="'.$value['id'].'" value="'. $s_val .'" class="mini" '. $s_edit .' />';
					$output .= '<div id="'.$value['id'].'-slider" class="smof_sliderui" style="margin-left: 7px;" '. $s_data .'></div>';
				break;
				//Switch option
				case 'switch':
					if (!isset($smof_data[$value['id']])) {
						$smof_data[$value['id']] = 0;
					}
					$fold = '';
					if (array_key_exists("folds",$value)) $fold="s_fld ";
					$cb_enabled = $cb_disabled = '';//no errors, please
					//Get selected
					if ($smof_data[$value['id']] == 1){
						$cb_enabled = ' selected';
						$cb_disabled = '';
					}else{
						$cb_enabled = '';
						$cb_disabled = ' selected';
					}
					//Label ON
					if(!isset($value['on'])){
						$on = "On";
					}else{
						$on = $value['on'];
					}
					//Label OFF
					if(!isset($value['off'])){
						$off = "Off";
					}else{
						$off = $value['off'];
					}
          
         
					$output .= '<p class="switch-options">';
						$output .= '<label class="'.$fold.'cb-enable'. $cb_enabled .'" data-id="'.$value['id'].'"><span>'. $on .'</span></label>';
						$output .= '<label class="'.$fold.'cb-disable'. $cb_disabled .'" data-id="'.$value['id'].'"><span>'. $off .'</span></label>';
						$output .= '<input type="hidden" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
						$output .= '<input type="checkbox" id="'.$value['id'].'" class="'.$fold.'checkbox of-input main_checkbox" name="'.$value['id'].'"  value="1" '. checked($smof_data[$value['id']], 1, false) .' />';
					$output .= '</p>';
				break;
				// Uploader 3.5
				case "upload":
				case "media":
					if(!isset($value['mod'])) $value['mod'] = '';
					$u_val = '';
					if($smof_data[$value['id']]){
						$u_val = stripslashes($smof_data[$value['id']]);
					}
					$output .= Options_Machine::optionsframework_media_uploader_function($value['id'],$u_val, $value['mod']);
				break;
				// imsc additional
				//drag & drop sidenav icon manager
				case 'sidenavico':
					$output .= '<div class="slider"><ul id="'.$value['id'].'">';
					$slides = $smof_data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_sidenavico_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_sidenavico_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button slide_add_button">'.__('Add New Icon', 'pbtheme').'</a></div>';
				break;
				//drag & drop sidebar manager
				case 'sidebar':
					$output .= '<div class="slider"><ul id="'.$value['id'].'">';
					$slides = $smof_data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_sidebar_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_sidebar_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button-primary slide_add_button">'.__('Add New Sidebar', 'pbtheme').'</a></div>';
				break;
				//drag & drop contact manager
				case 'contact':
					$output .= '<div class="slider"><ul id="'.$value['id'].'">';
					$slides = $smof_data[$value['id']];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_contact_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_contact_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button-primary slide_add_button">'.__('Add New Contact', 'pbtheme').'</a></div>';
				break;
				//drag & drop language manager
				case 'language':
					$_id = strip_tags( strtolower($value['id']) );
					$output .= '<div class="slider"><ul id="'.$value['id'].'">';
					$slides = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					$count = count($slides);
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_language_function($value['id'],$value['std'],$oldorder,$order);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_language_function($value['id'],$value['std'],$oldorder,$order);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button-primary slide_add_button">'.__('Add New Language', 'pbtheme').'</a></div>';
				break;
				//group extension
				case 'group':
					$icon = '';
					if(isset($value['icon'])){
						$icon = $value['icon'].' ';
					}
					$groups_ext .= '<button id="'. $value['id'] .'" type="button" class="button-primary">'. $icon . $value['name'] .'</button>';
				break;
				case 'demoplugins' :
					$pbuilder = false;
					$revslider = false;
					$fonts = false;
					$woo = false; 
					$ctimeline = false;
					$allaround = false;
					if ( in_array( 'profit_builder/profit_builder.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'Profit Builder - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$pbuilder = true;
					}
					else {
						$output .= 'Profit Builder - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					if ( in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'Revolution Slider - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$revslider = true;
					}
					else {
						$output .= 'Revolution Slider - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					if ( in_array( 'wp-visual-icon-fonts/wp_visual_icon_fonts.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'WP Visual Icon Fonts - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$fonts = true;
					}
					else {
						$output .= 'WP Visual Icon Fonts - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'WooCommerce - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$woo = true;
					}
					else {
						$output .= 'WooCommerce - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					if ( in_array( 'content_timeline/content_timeline.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'Content Timeline - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$ctimeline = true;
					}
					else {
						$output .= 'Content Timeline - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					if ( in_array( 'all_around/all_around.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$output .= 'All Around Slider - <span class="green">'.__('ACTIVE', 'pbtheme').'</span>';
						$allaround = true;
					}
					else {
						$output .= 'All Around Slider - <span class="red">'.__('NOT ACTIVE', 'pbtheme').'</span>';
					}
					$output .= '<br/>';
					$output .= '<br/><br/>';
					if ( $pbuilder === false || $revslider === false || $fonts === false || $woo === false || $ctimeline === false || $allaround === false ) {
						$url = admin_url('themes.php?page=install-required-plugins');
						$output .= sprintf( '%4$s %1$s<br/><br/><a href="%2$s" class="button-primary">%3$s</a>', __('Your plugins are not properly installed or activated. Click the Install and Activate Plugins button to install all the plugins needed for PBTheme demo installation.', 'pbtheme'), $url, __('Install and Activate Plugins', 'pbtheme'), '<span class="red">IMPORTANT</span>' );
					}
					else {
						$output .= __('This step has been successfully completed!', 'pbtheme').' <span class="green">'.__('OK', 'pbtheme').'</span>';
					}
				break;
				case 'demoimages' :
					$test_images = array(3595,237,306,424,1276,769,1390,2454);
					$tested_images = array();
					$count = 0;
					foreach ( $test_images as $test_image ) {
						$count++;
						if ( $test_image_current = wp_get_attachment_image( $test_image, 'thumbnail' ) ) {
							$output .= $test_image_current;
							$tested_images[$count] = true;
						}
						else {
							$output .= '<span class="no_image"></span>';
							$tested_images[$count] = false;
						}
					}
					$output .= '<br/><br/>';
					if ( in_array(false, $tested_images) ) {
						$url = admin_url('import.php?import=wordpress');
						$output .= sprintf( '%4$s %1$s<br/><br/><a href="%2$s" class="button-primary">%3$s</a><br/>', __('You have not imported all the images needed for the demo. Click the Import Images XML button to import the images XML file (this file can be found in the PBTheme full archive in the /demo/ folder.', 'pbtheme'), $url, __('Import Images XML', 'pbtheme'), '<span class="red">IMPORTANT</span>' );
					}
					else {
						$output .= __('This step has been successfully completed!', 'pbtheme').' <span class="green">'.__('OK', 'pbtheme').'</span>';
					}
				break;
				case 'democontent' :
					if ( !get_transient('PBTheme_Demo_Installation') ) {
						$output .= __('Make sure you have completed previous steps before clicking the Install Demo Content button.', 'pbtheme');
						$output .= '<br/><br/><a href="#" id="demo_installation" class="button-primary">'.__('Install Demo Content', 'pbtheme').'</a>';
					}
					else {
						$output .= __('Your Demo Content has been successfully installed!', 'pbtheme').' <span class="green">'.__('OK', 'pbtheme').'</span>';
					}
				break;
				case 'demotemplates' :
					$output .= __('Click the Install Templates button to import all PBTheme included templates in the Profit Builder.', 'pbtheme');
					$output .= '<br/><br/><a href="#" id="demo_templates" class="button-primary">'.__('Install Templates', 'pbtheme').'</a>';
				break;
			}
			do_action('optionsframework_machine_loop', array(
					'options'	=> $options,
					'smof_data'	=> $smof_data,
					'defaults'	=> $defaults,
					'counter'	=> $counter,
					'menu'		=> $menu,
					'output'	=> $output,
					'group'		=> $groups_ext,
					'value'		=> $value
				));
			$output .= $smof_output;
			//description of each option
			if ( $value['type'] != 'heading' ) {
				if(!isset($value['desc'])){ $explain_value = ''; } else {
					$explain_value = '<div class="explain">'. $value['desc'] .'</div>'."\n"; 
				}
				$output .= '</div>'.$explain_value."\n";
				$output .= '<div class="clear"> </div></div></div>'."\n";
				}
			} /* condition empty end */
		}
		$output .= '</div>';
		do_action('optionsframework_machine_after', array(
					'options'		=> $options,
					'smof_data'		=> $smof_data,
					'defaults'		=> $defaults,
					'counter'		=> $counter,
					'menu'			=> $menu,
					'output'		=> $output,
					'group'			=> $groups_ext,
					'value'			=> $value
				));
		$output .= $smof_output;
		return array($output,$menu,$defaults,$groups_ext);
	}
	/**
	 * Native media library uploader
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_media_uploader_function($id,$std,$mod){
		$data = of_get_options();
		$smof_data = of_get_options();
		$uploader = '';
		$upload = $smof_data[$id];
		$hide = '';
		if ($mod == "min") {$hide ='hide';}
		if ( $upload != "") { $val = $upload; } else { $val = $std; }
		$uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';	
		//Upload controls DIV
		$uploader .= '<div class="upload_button_div">';
		//If the user has WP3.5+ show upload/remove button
		if ( function_exists( 'wp_enqueue_media' ) ) {
			$uploader .= '<span class="button media_upload_button" id="'.$id.'">'.__('Upload', 'pbtheme').'</span>';
			if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
			$uploader .= '<span class="button remove-image '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		}
		else 
		{
			$output .= '<p class="upload-notice"><i>'.__('Upgrade your version of WordPress for full media support.', 'pbtheme').'</i></p>';
		}
		$uploader .='</div>' . "\n";
		//Preview
		$uploader .= '<div class="screenshot">';
		if(!empty($upload)){	
			$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
			$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			$uploader .= '</a>';
			}
		$uploader .= '</div>';
		$uploader .= '<div class="clear"></div>' . "\n"; 
		return $uploader;
	}
	/**
	 * Drag and drop slides manager
	 *
	 * @uses get_theme_mod()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_slider_function($id,$std,$oldorder,$order){
		$data = of_get_options();
		$smof_data = of_get_options();
		$slider = '';
		$slide = array();
		$slide = $smof_data[$id];
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else { $val = $std; }
		//initialize all vars
		$slidevars = array('title','url','link','description');
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Slide '.$order.'</strong>';
		}
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<a class="slide_edit_button" href="#">Edit</a></div>';
		$slider .= '<div class="slide_body">';
		$slider .= '<label>'.__('Title', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		$slider .= '<label>'.__('Image URL', 'pbtheme').'</label>';
		$slider .= '<input class="upload slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'">'.__('Upload', 'pbtheme').'</span>';
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button remove-image '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">'.__('Remove', 'pbtheme').'</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
			$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
			$slider .= '</a>';
			}
		$slider .= '</div>';	
		$slider .= '<label>'.__('Link URL (optional)', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';
		$slider .= '<label>'.__('Description (optional)', 'pbtheme').'</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
		$slider .= '<a class="slide_delete_button" href="#">'.__('Delete', 'pbtheme').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '</div>';
		$slider .= '</li>';
		return $slider;
	}
	/**
	 * imsc additional
	 */
	public static function optionsframework_sidenavico_function($id,$std,$oldorder,$order){
		$data = of_get_options();
		$smof_data = of_get_options();
		$slider = '';
		$slide = array();
		$slide = $smof_data[$id];
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		//initialize all vars
		$slidevars = array('icon', 'url','title');
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__('Icon', 'pbtheme').' '.$order.'</strong>';
		}
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<a class="slide_edit_button" href="#">'.__('Edit', 'pbtheme').'</a></div>';
		$slider .= '<div class="slide_body">';
		$slider .= '<label>'.__('Icon', 'pbtheme').'</label>';
		$slider .= '<textarea class="slide of-input of-slider-icon" name="'. $id .'['.$order.'][icon]" id="'. $id .'_'.$order .'_slide_icon" />' . stripslashes($val['icon']) . '</textarea>';
		$slider .= '<label>'.__('URL', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-url" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. stripslashes($val['url']) .'" />';
		$slider .= '<label>'.__('Title', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		$slider .= '<a href="#" class="button slide_delete_button" title="'.__('Delete Sidebar', 'pbtheme').'">'.__('Delete Sidebar', 'pbtheme').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '</div>';
		$slider .= '</li>';
		return $slider;
	}
	public static function optionsframework_sidebar_function($id,$std,$oldorder,$order){
		$data = of_get_options();
		$smof_data = of_get_options();
		$slider = '';
		$slide = array();
		$slide = $smof_data[$id];
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		//initialize all vars
		$slidevars = array('title');
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__('Sidebar', 'pbtheme').' '.$order.'</strong>';
		}
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<a class="slide_edit_button" href="#">'.__('Edit', 'pbtheme').'</a></div>';
		$slider .= '<div class="slide_body">';
		$slider .= '<label>'.__('Title', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		$slider .= '<a href="#" class="button-primary slide_delete_button" title="'.__('Delete Sidebar', 'pbtheme').'">'.__('Delete Sidebar', 'pbtheme').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '</div>';
		$slider .= '</li>';
		return $slider;
	}
	public static function optionsframework_contact_function($id,$std,$oldorder,$order){
		$data = of_get_options();
		$smof_data = of_get_options();
		$slider = '';
		$slide = array();
		$slide = $smof_data[$id];
		$socialnetworks = array ();
		$socialnetworks = glob( get_template_directory().'/images/socialnetworks/*' );
		$socialnetworks = array_filter( $socialnetworks, 'is_file' );
		$socialnetworks = array_map( 'basename', $socialnetworks );	
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		//initialize all vars
		$slidevars = array('name','url','email','job','description','contact');
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
				if ( $slidevar = 'contact' ) { $val[$slidevar] = array( 1 => array( 'socialnetworks' => 'dark_facebook.png', 'socialnetworksurl' => '' ) );}
			}
		}
		//begin slider interface	
		if (!empty($val['name'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['name']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__('Team Member', 'pbtheme').' '. $order .'</strong>';
		}
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<a class="slide_edit_button" href="#">'.__('Edit', 'pbtheme').'</a></div>';
		$slider .= '<div class="slide_body">';
		$slider .= '<label>'.__('Name', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][name]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['name']) .'" />';
		$slider .= '<label>'.__('Picture', 'pbtheme').'</label>';
		$slider .= '<input class="upload slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'">'.__('Upload', 'pbtheme').'</span>';
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button remove-image '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">'.__('Remove', 'pbtheme').'</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
			$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
			$slider .= '</a>';
			}
		$slider .= '</div>';	
		$slider .= '<label>'.__('Email', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][email]" id="'. $id .'_'.$order .'_slide_email" value="'. $val['email'] .'" />';
		$slider .= '<label>'.__('Job', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][job]" id="'. $id .'_'.$order .'_slide_phone" value="'. $val['job'] .'" />';	
		$slider .= '<label>'.__('Description (optional)', 'pbtheme').'</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
		$slider .= '<label>'.__('Social Networks', 'pbtheme').'</label>';
		$contacts = isset($data[$id][$oldorder]['contact']) && !empty($data[$id][$oldorder]['contact']) ? $data[$id][$oldorder]['contact'] : $std;
		$num = 1;
		$slider .= '<div class="of-socials-container">';
		foreach ( $contacts as $contact ) {
			$slider .= '<div class="of-regular-element">';
			$slider .= '<img class="socialnetwork-icon" width="32" height="32" src="' . get_template_directory_uri() . '/images/socialnetworks/' . $contact['socialnetworks'] . '">';
			$slider .= '<a href="#" class="network_delete_button" title="'.__('Delete Network', 'pbtheme').'">'.__('Delete Network', 'pbtheme').'</a>';
			$slider .= '<input class="slide of-input socialnetwork" name="'. $id .'['.$order.'][contact]['. $num .'][socialnetworksurl]" id="'. $id .'_'.$order .'_slide_socialnetworksurl" value="'. $contact['socialnetworksurl'] .'" />';
			$socialnetwork_select = '<div class="select_wrapper">';
			$socialnetwork_select .= '<select class="select of-input socialnetwork-select" name="'. $id .'['.$order.'][contact]['. $num .'][socialnetworks]" id="'. $id .'_'.$order .'_slide_socialnetworks">';
			foreach ( $socialnetworks as $socialnetwork ) {
				$selected = ( $contact['socialnetworks'] == $socialnetwork ) ? "selected = 'selected'" : '';
				$socialnetwork_select .= '<option value="'. $socialnetwork .'" '. $selected .'>'. $socialnetwork .'</option>';
			}
			$socialnetwork_select .= '</select></div>';
			$slider .= $socialnetwork_select;
			$slider .= '</div>';
			$num++;
		}
		$slider .= '</div>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '<a href="#" class="button-primary network_add_button" title="'.__('Add Network', 'pbtheme').'">'.__('Add Social Network', 'pbtheme').'</a>';
		$slider .= '<a class="button-primary slide_delete_button" href="#" title="'.__('Delete Contact', 'pbtheme').'">'.__('Delete Contact', 'pbtheme').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '</div>';
		$slider .= '</li>';
		return $slider;
	}
	public static function optionsframework_language_function($id,$std,$oldorder,$order){
		$data = of_get_options();
		$smof_data = of_get_options();
		$slider = '';
		$slide = array();
		$slide = $smof_data[$id];
		$flags = array ();
		$flags = glob( get_template_directory().'/images/flags/*' );
		$flags = array_filter( $flags, 'is_file' );
		$flags = array_map( 'basename', $flags );	
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else { $val = $std; }
		//initialize all vars
		$slidevars = array('language' ,'langurl');
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		//begin slider interface	
		if (!empty($val['language'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['language']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__('Language', 'pbtheme').' '.$order.'</strong>';
		}
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
		$slider .= '<a class="slide_edit_button" href="#">'.__('Edit', 'pbtheme').'</a></div>';
		$slider .= '<div class="slide_body">';
		$flags_select = '<div class="select_wrapper">';
		$flags_select .= '<select class="select of-input flag-select" name="'. $id .'['.$order.'][flag]" id="'. $id .'_'.$order .'_slide_flag">';
		foreach ( $flags as $flag ) {
			$selected = ( isset($val['flag']) && $val['flag']  == $flag ? "selected = 'selected'" : '' );
			$flags_select .= '<option value="'. $flag .'" '. $selected .'>'. $flag .'</option>';
		}
		$flags_select .= '</select></div>';
		$slider .= $flags_select;
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '<label>'.__('Language', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-language" name="'. $id .'['.$order.'][language]" id="'. $id .'_'.$order .'_slide_language" value="'. stripslashes($val['language']) .'" />';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '<label>'.__('Language URL', 'pbtheme').'</label>';
		$slider .= '<input class="slide of-input of-slider-langurl" name="'. $id .'['.$order.'][langurl]" id="'. $id .'_'.$order .'_slide_langurl" value="'. stripslashes($val['langurl']) .'" />';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '<a href="#" class="button-primary slide_delete_button" title="'.__('Delete Language', 'pbtheme').'">'.__('Delete Language', 'pbtheme').'</a>';
		$slider .= '<div class="clear"></div>' . "\n";
		$slider .= '</div>';
		$slider .= '</li>';
		return $slider;
	}
}//end Options Machine class
?>