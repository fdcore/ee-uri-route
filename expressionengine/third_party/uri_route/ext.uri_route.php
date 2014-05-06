<?php
/*
 * URI Route for ExpressionEngine 2
 * @author Dmitriy <sealnetsoul@gmail.com>
 * @copyright Copyright (c) 2013 FDCore Studio <http://fdcore.com>
 *
*/
class Uri_route_ext {

    var $name		    = '';
    var $version 		= '1.6';
    var $description	= '';
    var $settings_exist	= 'n';
    var $docs_url		= 'http://fdcore.com/';
    var $settings        = array();

    /**
     * Constructor
     *
     * @param 	mixed	Settings array or empty string if none exist.
     */
    function __construct($settings='')
    {

        ee()->lang->loadfile('uri_route');

        $this->name = lang('uri_route_module_name');
        
        $this->description = lang('uri_route_module_description');

        $this->settings = $settings;
    }
    // END


    function activate_extension()
    {

        $data = array(
            'class'		=> __CLASS__,
            'method'	=> 'switch_template',
            'hook'		=> 'core_template_route',
            'settings'	=> serialize($this->settings),
            'priority'	=> 10,
            'version'	=> $this->version,
            'enabled'	=> 'y'
        );

        ee()->db->insert('extensions', $data);
    }

    function switch_template($uri_string)
    {
        $params = explode('/', $uri_string);
        $new_uri_string = '';
        $result_rules = array();
        $win_rule = array();

        if (isset($params[0])) $params['template_group'] = $params[0]; else $params['template_group'] = '';
        if (isset($params[1])) $params['template'] = $params[1]; else $params['template'] = '';

        if ($uri_string != '' && $uri_string != '/') {

            $query = ee()->db->get_where('exp_uri_route', array('site_id' => ee()->config->item('site_id'), 'enable' => 'on'));

            if ($query->num_rows() > 0) {

                foreach ($query->result_array() as $row)
                    $result_rules[] = $row;

                foreach ($result_rules as $rule) {

                    if (preg_match($rule['template_rules'], $uri_string)) {

                        $win_rule = $rule;

                        if(intval($rule['start_date']) > 0 && intval($rule['end_date']) > 0){
                            $start_date = intval($rule['start_date']);
                            $end_date   = intval($rule['end_date']);
                            
                            // echo date('Y.m.d H:i:s').'<br>';
                            // echo date('Y.m.d H:i:s', $start_date).'<br>';
                            // echo date('Y.m.d H:i:s', $end_date).'<br>';
                            
                            if(time() < $start_date) break;
                            if($end_date < time()) break;
                        }


                        $rule['group_id'] = str_replace(' ', '', $rule['group_id']);
                        $rule['member_id'] = str_replace(' ', '', $rule['member_id']);

                        if(strstr($rule['group_id'], ',')) $rule['group_id'] = explode(',', $rule['group_id']); else $rule['group_id'] = array($rule['group_id']);
                        if(strstr($rule['member_id'], ',')) $rule['member_id'] = explode(',', $rule['member_id']); else $rule['member_id'] = array($rule['member_id']);

                        if (count($rule['group_id']) == 1 && in_array('0', $rule['group_id'])) {
                            $new_uri_string = preg_replace($rule['template_rules'], $rule['template_replace'], $uri_string);
                        }

                        if (in_array( ee()->session->userdata['group_id'], $rule['group_id'])) {

                            $new_uri_string = preg_replace($rule['template_rules'], $rule['template_replace'], $uri_string);
                            break;
                        }

                        if (count($rule['member_id']) > 0 && $rule['member_id'][0] != 0 && in_array(ee()->session->userdata['member_id'], $rule['member_id'])) {
                            $new_uri_string = preg_replace($rule['template_rules'], $rule['template_replace'], $uri_string);
                            break;
                        }

                        break;
                    }
                }

                if ($new_uri_string != '') {

                    if(intval($rule['redirect']) > 0){
                        ee()->load->helper('url');
                        redirect($new_uri_string, 'location', intval($rule['redirect']));
                        exit();
                    }

                    $new_uri_string = trim($new_uri_string, '/');

                    if(strstr($new_uri_string, '/'))
                    {
                        $segs = explode('/', $new_uri_string);
                    } else{
                        $segs = array($new_uri_string);
                    }

                    if ($win_rule['replace_uri'] == 'y') {
                        ee()->uri->uri_string = $new_uri_string;

                        if(count($segs) > 0){
                            $segment_index = 1;

                            foreach($segs as $s){
                                ee()->uri->segments[$segment_index] = $s;
                                $segment_index++;
                            }
                        }
                    }

                    return $segs;

                }
            }
        }
    }

    function update_extension($current = '')
    {
        return FALSE;
    }
    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }

}
// END CLASS