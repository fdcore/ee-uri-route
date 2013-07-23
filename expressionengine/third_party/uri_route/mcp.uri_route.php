<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * URI Route for ExpressionEngine 2
 * @author Dmitriy Nyashkin <sealnetsoul@gmail.com>
 * @copyright Copyright (c) 2011 - 2013 FDCore Studio <http://fdcore.com>
*/
class Uri_route_mcp {

	public $vars = array();
	
	function __construct()
	{
		$this->EE =& get_instance();

        $this->mod_name = 'uri_route';
        $this->root_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name;

		ee()->cp->set_breadcrumb($this->root_url,ee()->lang->line('uri_route_module_name'));
		
	}

    function index(){

        $this->_get_member_groups();

        ee()->load->library('table');

        ee()->cp->set_right_nav(array(
            'create_rule'	=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'
            .AMP.'module='.$this->mod_name.AMP.'method=create'
        ));

		ee()->view->cp_page_title = ee()->lang->line('uri_route_module_name');
		
		$query = ee()->db->query("SELECT * FROM exp_uri_route WHERE site_id = ?", ee()->config->item('site_id'));
				
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
                $row['members'] = $this->_get_member_list_from_string($row['member_id'], true);
				$this->vars['rules'][] = $row;
			}
		}
		
		return ee()->load->view('index', $this->vars, TRUE);
	}
	
	function get_tpl_id($group,$template){
		
		$query = ee()->db->query("SELECT t.template_id, g.group_name, t.template_name
		FROM exp_templates AS t, exp_template_groups AS g
		WHERE t.template_name =  ?
		AND g.group_name =  ?
		AND t.group_id = g.group_id", array($template, $group));
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			
			return $row->template_id;
			
		} else return false;		
		
		
	}
	function create(){
				
		ee()->view->cp_page_title = ee()->lang->line('create_rule_title');

		$this->_get_member_groups();

        ee()->cp->set_right_nav(array('rules_list'	=> $this->root_url));
        ee()->cp->add_js_script(
            array(
                'ui'      => array('datepicker'),
            )
        );
		/*
			POST Fields
		*/
		$name 				= ee()->input->post('name', true);
		$username 			= ee()->input->post('username');
		$group_id 			= ee()->input->post('group_id');
		$template_rules 	= ee()->input->post('template_rules');					
		$template_replace   = ee()->input->post('template_replace');
		$replace_uri 		= ee()->input->post('replace_uri');
        $enable_rule 		= ee()->input->post('enable');

        $start_date 		= ee()->input->post('start_date');
        $end_date 		    = ee()->input->post('end_date');
        $redirect_type 		= ee()->input->post('redirect_type');
        $redirect 		    = ee()->input->post('redirect');
        $start_end_date 		= ee()->input->post('start_end_date');

		if(count($_POST)  > 0){
			
			$insert = array();
			
			try {
				
				if(!$name) throw new Exception(ee()->lang->line('error_setname'));
				
				$insert['name'] = $name;
				
				if($replace_uri == 'y') $insert['replace_uri'] = 'y'; else $insert['replace_uri'] = 'n';
                if($enable_rule == 'on') $insert['enable'] = 'on'; else $insert['enable'] = 'off';

				$insert['site_id'] = ee()->config->item('site_id');
				
				if($username && $username!==''){
					
					$query = ee()->db->get_where('exp_members', array('username' => $username));
					
					if ($query->num_rows() != 1)
                        throw new Exception(ee()->lang->line('error_setuser'));

					$row = $query->row();
					$insert['member_id'] =$row->member_id;
				}
								
				if($group_id) {

                    foreach($group_id as $k=>$v) $group_id[$k] = intval($v);

                    $insert['group_id'] = implode(',', $group_id);

                } else $insert['group_id'] = 0;

								
				$insert['template_rules'] = $template_rules;
                $insert['template_replace'] = $template_replace;

                if($redirect == 'y'){
                    $insert['redirect'] = intval($redirect_type);
                }

                if($start_end_date == 'y' && $end_date){
                    if($start_date) $insert['start_date'] = @strtotime($start_date); else $insert['start_date'] = time();

                    $insert['end_date'] = @strtotime($end_date);

                    // protect for unlimited time
                    if($insert['end_date'] < $insert['start_date']){
                        $insert['start_date'] = 0;
                        $insert['end_date'] = 0;

                        ee()->session->set_flashdata('message_failure', '"Start and End use rule" is invalid');
                    }
                } else {
                    $insert['start_date'] = 0;
                    $insert['end_date'] = 0;
                }

                $old_error = error_reporting(0); // Turn off error reporting
                $match = preg_match($template_rules, '');

                if ($match === false)
                {
                    $this->vars['message_failure'] = 'Invalid regex pattern.';

                } else {

                    error_reporting($old_error);

                    ee()->db->insert('exp_uri_route', $insert);

                    ee()->session->set_flashdata('message_success', ee()->lang->line('succeful_add'));

                    ee()->functions->redirect($this->root_url);
                }


				
			} catch (Exception $e) {
				
				ee()->session->set_flashdata('message_failure', $e->getMessage());
				ee()->functions->redirect($this->root_url.AMP.'method=create');
			}			
			
			
		}
					
		return ee()->load->view('create', $this->vars, TRUE);
	}
	

	function delete(){
		
		$rule_id = intval(ee()->input->get('id', true));

		if($rule_id > 0){
			ee()->session->set_flashdata('message_success', 'Rule deleted');
			ee()->db->delete('exp_uri_route', array('id' => $rule_id));
		}

		ee()->functions->redirect($this->root_url);
	}
	
	
	function edit(){
		
		ee()->view->cp_page_title = ee()->lang->line('edit_rule');
        ee()->cp->add_js_script(
            array(
                'ui'      => array('datepicker'),
            )
        );
        ee()->cp->set_right_nav(array(
            'create_rule' => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name.AMP.'method=create',
            'rules_list'  => $this->root_url)
        );

		$rule_id = intval(ee()->input->get('id', true));		
		
		if($rule_id == 0){
			ee()->session->set_flashdata('message_failure', 'Rule not found');
			ee()->functions->redirect($this->root_url);
		}
		
		$query = ee()->db->get_where('exp_uri_route', array('site_id' => ee()->config->item('site_id'), 'id' => $rule_id), 1);
		
		if ($query->num_rows() == 0){
			
			ee()->session->set_flashdata('message_failure', 'Rule not found');
			ee()->functions->redirect($this->root_url);
		}
		
		$rule_row = $query->row_array();
		$this->_get_member_groups();
		
		/*
			POST Fields
		*/
		$name 				= ee()->input->post('name', true);
		$username 			= ee()->input->post('username');
		$group_id 			= ee()->input->post('group_id');

		$template_rules 	= ee()->input->post('template_rules');					
		$template_replace   = ee()->input->post('template_replace');
		$replace_uri 		= ee()->input->post('replace_uri');
		$enable_rule 		= ee()->input->post('enable');

        $start_date 		= ee()->input->post('start_date');
        $end_date 		    = ee()->input->post('end_date');
        $redirect_type 		= ee()->input->post('redirect_type');
        $redirect 		    = ee()->input->post('redirect');
        $start_end_date 	= ee()->input->post('start_end_date');

		if(count($_POST)  > 0){
			
			$insert = array();
			
			try {
				
				if(!$name) throw new Exception(ee()->lang->line('error_setname'));
				
                $insert['name'] = $name;
				$insert['site_id'] = ee()->config->item('site_id');
			
                if($replace_uri == 'y') $insert['replace_uri'] = 'y'; else $insert['replace_uri'] = 'n';
                if($enable_rule == 'on') $insert['enable'] = 'on'; else $insert['enable'] = 'off';


                // set user for rules ---------------------
				if($username && $username!==''){

                    if(strstr($username, ',')){
                        $username = explode(',', $username);
                    } else $username = array($username);

                    $username = array_unique($username);

                    $insert['member_id'] = '';
                    $member_id_list = array();

                    foreach($username as $mbr){
                        $query = ee()->db->get_where('exp_members', array('username' => trim($mbr)));
                        if ($query->num_rows() != 1) throw new Exception(ee()->lang->line('error_setuser'));
                        $row = $query->row();
                        if(!in_array($row->member_id, $member_id_list)) $member_id_list[]=$row->member_id;
                    }

                    $insert['member_id'] = implode(', ', $member_id_list);

                    // удаляем лишниюю запятую
                    if($insert['member_id'] !== '' && strstr(',', $insert['member_id'])) $insert['member_id'] = substr($insert['member_id'], 0, -1);

                    unset($member_id_list, $username);
				}

                if($redirect == 'y'){
                    $insert['redirect'] = intval($redirect_type);

                } else $insert['redirect'] = '';

                if($start_end_date == 'y' && $end_date){

                    if($start_date) $insert['start_date'] = @strtotime($start_date); else $insert['start_date'] = time();

                    $insert['end_date'] = @strtotime($end_date);

                    // protect for unlimited time
                    if($insert['end_date'] < $insert['start_date']){
                        $insert['start_date'] = 0;
                        $insert['end_date'] = 0;

                        ee()->session->set_flashdata('message_failure', '"Start and End use rule" is invalid');
                    }
                } else {
                    $insert['start_date'] = 0;
                    $insert['end_date'] = 0;
                }

                // set groups for rules ---------------------
                if($group_id) {

                    foreach($group_id as $k=>$v) $group_id[$k] = intval($v);

                    $insert['group_id'] = implode(',', $group_id);

                    unset($group_id);

                } else $insert['group_id'] = 0;

                // set regex for rules ---------------------
				$insert['template_rules'] = $template_rules;
                $insert['template_replace'] = $template_replace;

                $old_error = error_reporting(0); // Turn off error reporting

                $match = preg_match($template_rules, '');

                if ($match === false)
                {
                    $this->vars['message_failure'] = sprintf('Rule "%s" is not valid regex pattern.', $template_rules);

                } else {

                    error_reporting($old_error);

                    ee()->db->update('exp_uri_route', $insert, array("id" => $rule_id));

                    ee()->session->set_flashdata('message_success', ee()->lang->line('succeful_update'));

                    ee()->functions->redirect($this->root_url);
                }

				
			} catch (Exception $e) {
				
				ee()->session->set_flashdata('message_failure', $e->getMessage());
				ee()->functions->redirect($this->root_url.AMP.'method=edit'.AMP.'id='.$rule_id);
			}			
			
			
		}				
		
		if($rule_row['member_id'] != ''){
			$rule_row['username'] = $this->_get_member_list_from_string($rule_row['member_id'], false, true);
		}
		
    		
		if($rule_row['group_id'] !==""){
		
			$query = ee()->db->get_where('exp_member_groups', array('site_id' => ee()->config->item('site_id'), 'group_id' => $rule_row['group_id']), 1);
			
              if($query->num_rows() > 0){

                $row = $query->row_array();

                $rule_row['group_title'] = $row['group_title'];

              } else $rule_row['group_title'] = '';
      	
		}
		
				
		$this->vars['rule'] = $rule_row;
		
		return ee()->load->view('edit', $this->vars, TRUE);
	}

    function _get_member_list_from_string($string, $cover_link = false, $show_all = false){

        if($string == '') return '';

        if(strstr($string, ',')) $string = explode(',', $string); else $string = array(trim($string));

        $return  = '';
        $count   = 0;

        foreach($string as $mbr_id){
            $count++;
            $mbr_id = intval($mbr_id);
            $query = ee()->db->get_where('exp_members', array('member_id' => $mbr_id), 1);
            
            if($query->num_rows() == 0) continue;
            
            $row = $query->row_array();

            if($cover_link)
                $return.=anchor(BASE.AMP.'C=myaccount'.AMP.'id='.intval($mbr_id), $row['username'], array('title' => $row['screen_name'], 'target' => '_blank')).', ';

            else $return.=$row['username'].', ';

            //$return = str_replace(' ', '', $return);

            if($count > 9 && $show_all == false){
                $return = substr($return, 0, -2);
                $return.=" ..., ";
                break;
            }
        }

        $return = substr($return, 0, -2);
        return $return;

    }
	

	function _get_member_groups(){
		
		ee()->db->where('site_id', ee()->config->item('site_id'));
		ee()->db->from('exp_member_groups');
	
		$query = ee()->db->get();
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$this->vars['member_groups'][$row['group_id']]=$row;
			}
		}


	}
	
	function get_memberlist(){
		
		$username = ee()->input->post('username', true);
		
		if($username == '') exit();
	
		ee()->db->like('username', $username, 'after'); 		
		ee()->db->limit(10);
		ee()->db->from('exp_members');
		
		$query = ee()->db->get();
			
		echo "<table class='mainTable'>";

		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				printf("<tr><td><a href='javascript:;' onclick=\"member('%s')\">%s (%s)</a></td></tr>", $row['username'], $row['username'], $row['email']);
			}
		}
						
		exit('</table>');
	}
}
// END CLASS

/* End of file mcp.uri_route.php */
/* Location: ./system/expressionengine/third_party/uri_route/mcp.uri_route.php */