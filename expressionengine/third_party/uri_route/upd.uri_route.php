<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * URI Route for ExpressionEngine 2
 * @author Dmitriy Nyashkin <sealnetsoul@gmail.com>
 * @copyright Copyright (c) 2011 - 2013 FDCore Studio <http://fdcore.com>
*/

class Uri_route_upd{ 
	
	var $version        = '1.5';
	
	function install(){
		
		$data = array(
			'module_name' => 'Uri_route' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);
		
		ee()->db->query("CREATE TABLE IF NOT EXISTS exp_uri_route (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL,
		  template_rules varchar(255) DEFAULT NULL,
		  group_id varchar(255)  NOT NULL DEFAULT '0',
		  site_id int(255) NOT NULL,
		  member_id varchar(255) NOT NULL DEFAULT '0',
		  template_replace varchar(255) DEFAULT NULL,
		  replace_uri varchar(1) DEFAULT NULL,
		  start_date INT( 255 ) NOT NULL DEFAULT  '0',
		  end_date INT( 255 ) NOT NULL DEFAULT  '0',
		  redirect VARCHAR( 255 ) NULL,
		  enable enum('on','off') COLLATE 'utf8_general_ci' NULL DEFAULT 'on',
		  PRIMARY KEY (id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		
		ee()->db->insert('modules', $data);		
		
		return true;
	}
	
	function update($current=''){
		
		if ($current == $this->version)
			{
				return FALSE;
			}
			return TRUE;
	}
	
	function uninstall(){
		
		ee()->load->dbforge();

        ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => 'Uri_route'));

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('module_name', 'Uri_route');
        ee()->db->delete('modules');

        ee()->dbforge->drop_table('uri_route');

        return TRUE;
	}
}