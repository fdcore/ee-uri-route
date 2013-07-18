<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * URI Route for ExpressionEngine 2
 * @author Dmitriy Nyashkin <sealnetsoul@gmail.com>
 * @copyright Copyright (c) 2011 - 2013 FDCore Studio <http://fdcore.com>
*/

class Uri_route_upd{ 
	
	var $version        = '1.4';
	
	function __construct() 
    { 
		$this->EE =& get_instance();
    }

	function install(){
		
		$data = array(
			'module_name' => 'Uri_route' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);
		
$this->EE->db->query("CREATE TABLE IF NOT EXISTS exp_uri_route (
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
		
		$this->EE->db->insert('modules', $data);		
		
		return true;
	}
	
	function update($current=''){
		
		if ($current == $this->version)
			{
				return FALSE;
			}

			if (version_compare($current, '1.3', '<='))
			{
                $this->EE->db->query("ALTER TABLE exp_uri_route ADD start_date INT( 255 ) NOT NULL DEFAULT '0' AFTER replace_uri");
                $this->EE->db->query("ALTER TABLE exp_uri_route ADD end_date INT( 255 ) NOT NULL DEFAULT '0' AFTER start_date");
                $this->EE->db->query("ALTER TABLE exp_uri_route ADD redirect VARCHAR( 255 ) NULL AFTER enable");
			}

			return TRUE;
	}
	
	function uninstall(){
		
		$this->EE->load->dbforge();

        $this->EE->db->select('module_id');
        $query = $this->EE->db->get_where('modules', array('module_name' => 'Uri_route'));

        $this->EE->db->where('module_id', $query->row('module_id'));
        $this->EE->db->delete('module_member_groups');

        $this->EE->db->where('module_name', 'Uri_route');
        $this->EE->db->delete('modules');

        $this->EE->dbforge->drop_table('uri_route');

        return TRUE;
	}
}