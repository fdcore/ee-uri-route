<?php

if(!isset($rules)){
	
	echo "<p class='notice'>".$this->lang->line('no_rules')."</p>";

} else {

    $this->table->set_template($cp_table_template);

    $this->table->set_heading(
        lang('status'),
        lang('redirect'),
        lang('field_name'),
        lang('template_rules'),
        lang('template_replace'),
        lang('member_group'),
        lang('member'),
        '',
        ''
    );

    $delete_link = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=uri_route'.AMP.'method=delete'.AMP.'id=';
    $edit_link = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=uri_route'.AMP.'method=edit'.AMP.'id=';

    foreach ($rules as $rule){

        if(strstr($rule['group_id'], ',')){
            $new_gr = '';
            $exgr = explode(',', $rule['group_id']);

            foreach($exgr as $group){
                if($group == 0) continue;
                $new_gr.='<a href="'.BASE.AMP.'C=members&M=edit_member_group&group_id='.$group.'" target="_blank">'.$member_groups[$group]['group_title'].'</a>, ';
            }

            $new_gr = substr($new_gr, count($new_gr)-1, -2);
            $rule['group_id'] = $new_gr;

        } else {

            if($rule['group_id'] == 0) $rule['group_id'] = 'all group'; else {
                $rule['group_id'] = '<a href="'.BASE.AMP.'C=members&M=edit_member_group&group_id='.$rule['group_id'].'" target="_blank">'.$member_groups[$rule['group_id']]['group_title'].'</a>';
            }
        }

        $this->table->add_row(
            ($rule['enable'] == 'on')?'<span class="ui-icon ui-icon-check"></span>':'<span class="ui-icon ui-icon-closethick"></span>',
            ($rule['redirect'] !='')?'<span class="ui-icon ui-icon-check" style="float: left;margin-top: -3px;"></span>'.$rule['redirect']:'<span class="ui-icon ui-icon-closethick"></span>',
            $rule['name'],
            $rule['template_rules'],
            $rule['template_replace'],
            $rule['group_id'],
            $rule['members'],
            "<a href='$edit_link{$rule['id']}'>".$this->lang->line('edit')."</a>",
            "<a href='$delete_link{$rule['id']}'>".$this->lang->line('delete')."</a>"
        );

    }

    echo $this->table->generate();
}
?>

<script type="text/javascript" charset="utf-8">
// <![CDATA[
$(document).ready(function() {
    $(".mainTable").tablesorter({
            headers: {0: {sorter: false}},
            textExtraction: "complex",          
            widgets: ["zebra"]
        });
});
// ]]>
</script>