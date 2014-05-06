<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=uri_route'.AMP.'method=create', '')?>

<?php
    if(isset($message_failure)){

        echo "<p class='notice'>$message_failure</p>";
    }
?>

<a href="javascript:;" onclick="$('#help_info').slideToggle();">Basic Syntax regular expressions</a>
<div id="help_info" style="display: none">
<table class="mainTable padTable">
    <tbody>
    <tr>
        <th>Operator</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>^</td>
        <td>The circumflex symbol marks the beginning of a pattern, although in some cases it can be omitted</td>
    </tr>
    <tr>
        <td>$</td>
        <td>Same as with the circumflex symbol, the dollar sign marks the end of a search pattern</td>
    </tr>
    <tr>
        <td>.</td>
        <td>The period matches any single character</td>
    </tr>
    <tr>
        <td>?</td>
        <td>It will match the preceding pattern zero or one times</td>
    </tr>
    <tr>
        <td>+</td>
        <td>It will match the preceding pattern one or more times</td>
    </tr>
    <tr>
        <td>*</td>
        <td>It will match the preceding pattern zero or more times</td>
    </tr>
    <tr>
        <td>|</td>
        <td>Boolean <em>OR</em></td>
    </tr>
    <tr>
        <td>-</td>
        <td>Matches a range of elements</td>
    </tr>
    <tr>
        <td>()</td>
        <td>Groups a different pattern elements together</td>
    </tr>
    <tr>
        <td>[]</td>
        <td>Matches any single character between the square brackets</td>
    </tr>
    <tr>
        <td>{min, max}</td>
        <td>It is used to match exact character counts</td>
    </tr>
    <tr>
        <td>\d</td>
        <td>Matches any single digit</td>
    </tr>
    <tr>
        <td>\D</td>
        <td>Matches any single non digit caharcter</td>
    </tr>
    <tr>
        <td>\w</td>
        <td>Matches any alpha numeric character including underscore (_)</td>
    </tr>
    <tr>
        <td>\W</td>
        <td>Matches any non alpha numeric character excluding the underscore character</td>
    </tr>
    <tr>
        <td>\s</td>
        <td>Matches whitespace character</td>
    </tr>
    </tbody>
</table>
<p>As an addition in PHP the forward slash character is escaped using the simple slash <em><strong>\</strong></em>. Example: <em><strong>&#8216;/he\/llo/&#8217;</strong></em></p>
<p><strong>To have a brief understanding how these operators are used, let&#8217;s have a look at a few examples:</strong></p>
<table class="mainTable padTable">
    <tbody>
    <tr>
        <th>Example</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>&#8216;|hello|&#8217;</td>
        <td>It will match the word <em>hello</em></td>
    </tr>
    <tr>
        <td>&#8216;|^hello|&#8217;</td>
        <td>It will match <em>hello</em> at the start of a string. Possible matches are <em>hello</em> or <em>helloworld</em>, but not <em>worldhello</em></td>
    </tr>
    <tr>
        <td>&#8216;|hello$|&#8217;</td>
        <td>It will match <em>hello</em> at the end of a string.</td>
    </tr>
    <tr>
        <td>&#8216;|he.o|&#8217;</td>
        <td>It will match any character between <em>he</em> and <em>o</em>. Possible matches are <em>helo</em> or <em>heyo</em>, but not <em>hello</em></td>
    </tr>
    <tr>
        <td>&#8216;|he?llo|&#8217;</td>
        <td>It will match either <em>llo</em> or <em>hello</em></td>
    </tr>
    <tr>
        <td>&#8216;|hello+|&#8217;</td>
        <td>It will match <em>hello</em> on or more time. E.g. <em>hello</em> or <em>hellohello</em></td>
    </tr>
    <tr>
        <td>&#8216;|he*llo|&#8217;</td>
        <td>Matches <em>llo</em>, <em>hello</em> or <em>hehello</em>, but not <em>hellooo</em></td>
    </tr>
    <tr>
        <td>&#8216;@hello|world@&#8217;</td>
        <td>It will either match the word <em>hello</em> or <em>world</em></td>
    </tr>
    <tr>
        <td>&#8216;|(A-Z)|&#8217;</td>
        <td>Using it with the hyphen character, this pattern will match every uppercase character from A to Z. E.g. A, B, C&#8230;</td>
    </tr>
    <tr>
        <td>&#8216;|[abc]|&#8217;</td>
        <td>It will match any single character <em>a</em>, <em>b</em> or <em>c</em></td>
    </tr>
    <tr>
        <td>&#8216;|abc{1}|&#8217;</td>
        <td>Matches precisely one <em>c</em> character after the characters <em>ab</em>. E.g. matches <em>abc</em>, but not <em>abcc</em></td>
    </tr>
    <tr>
        <td>&#8216;|abc{1,}|&#8217;</td>
        <td>Matches one or more <em>c</em> character after the characters <em>ab</em>. E.g. matches <em>abc</em> or <em>abcc</em></td>
    </tr>
    <tr>
        <td>&#8216;|abc{2,4}|&#8217;</td>
        <td>Matches between two and four <em>c</em> character after the characters <em>ab</em>. E.g. matches <em>abcc</em>, <em>abccc</em> or <em>abcccc</em>, but not <em>abc</em></td>
    </tr>
    </tbody>
</table>

<p>info from: <a href="http://www.noupe.com/php/php-regular-expressions.html" target="_blank">http://www.noupe.com/php/php-regular-expressions.html</a></p>
</div>

<table class="mainTable">
	<thead>
		<tr>
			<th style="width: 30%"><?=$this->lang->line('prefe')?></th>
			<th><?=$this->lang->line('setting')?></th>
		</tr>
	</thead>
	<tr class="odd">
		<td><label for="name"><em class="required">* </em><?=$this->lang->line('field_name')?></label></td>
		<td><input type="text" name="name" id="name" required="required" value="<?=$this->input->post('name')?>" /></td>
	</tr>
	<tr class="even">
		<td><label for="group_id"><em class="required">* </em><?=$this->lang->line('member_group')?></label></td>
		<td>
			<select name="group_id[]" id="group_id" size="<?=count($member_groups)+1;?>" multiple>
				<?php
                if($this->input->post('group_id') == false)
					echo "<option name='0' selected='selected'>".$this->lang->line('not_select')."</option>";
			    elseif(in_array(0, $this->input->post('group_id'))){
                    echo "<option name='0' selected='selected'>".$this->lang->line('not_select')."</option>";
                }  else echo "<option name='0'>".$this->lang->line('not_select')."</option>";


                if(isset($member_groups) && count($member_groups) > 0)
                    foreach($member_groups as $user)
                    {
                        $select=(in_array($user['group_id'], $this->input->post('group_id')))?'selected':'';
                        printf("<option value='%d' $select>%s</option>", $user['group_id'], $user['group_title']);
                    }
				?>
			</select>
		</td>		
	</tr>
	<tr class="odd">
		<td><label for="username"><?=$this->lang->line('member')?></label><div class="subtext"><?=$this->lang->line('member_notice')?></div></td>
		<td><input type="text" name="username" id="username" value="<?=$this->input->post('username')?>" /><div id="member_list"></div></td>
	</tr>
	
	<tr class="even">
		<td><label for="template_rules"><em class="required">* </em><?=$this->lang->line('template_rules')?></label><div class="subtext"><?=$this->lang->line('template_rules_help')?></div></td>
		<td><input type="text" name="template_rules" id="template_rules" value="<?=$this->input->post('template_rules')?>" placeholder="|^u([0-9]+)|" />
    		  <small>Example: <strong>|^u([0-9]+)|</strong> , <strong>|^about/([a-zA-Z0-9]+)|</strong></small>
		</td>
	</tr>
	
	
	<tr class="odd">
		<td><label for="template_replace"><em class="required">* </em><?=$this->lang->line('template_replace')?></label><div class="subtext"><?=$this->lang->line('template_replace_help')?></div></td>
		<td><input type="text" name="template_replace" id="template_replace" value="<?=$this->input->post('template_replace')?>" placeholder="/news/news_full_page/$1/$2" /></td>
	</tr>
  
	<tr class="even">
		<td><label for="replace_uri"><?=$this->lang->line('replace_uri')?></label><div class="subtext"><?=$this->lang->line('replace_uri_help')?></div></td>
		<td>
      <input name="replace_uri" value="y" type="checkbox">
		</td>		
	</tr>

    <tr class="odd">
        <td><label for="replace_uri"><?=$this->lang->line('enable_rule')?></label></td>
        <td>
            <input name="enable" value="on" type="checkbox" checked='checked'>
        </td>
    </tr>

    <tr class="even">
        <td><label for="redirect_checkbox"><?=$this->lang->line('use_redirect')?></label><div class="subtext"><?=$this->lang->line('use_redirect_descr')?></div></td>
        <td>
            <input name="redirect" value="y" type="checkbox" id="redirect_checkbox" >&nbsp;
            <span style="opacity:.4" id="redirect_input">
            <select name="redirect_type" disabled id="redirect_input">
                <option value="301">permanent (301)</option>
                <option value="302">temp (302)</option>
                <option value="303">seeother (303)</option>
                <option value="410">gone (410)</option>
            </select>
                </span>
        </td>
    </tr>

    <tr class="odd">
        <td><label for="start_end_date_checkbox"><?=$this->lang->line('start_end_rule')?></label></td>
        <td>
            <input name="start_end_date" value="y" type="checkbox" id="start_end_date_checkbox">&nbsp;
            <span style="opacity:.4" id="start_end_date_input">
            Start: <input type="text" name="start_date" class="datepicker" disabled placeholder="2013-08-07 12:34:56" style="width: 130px;" />
            End: <input type="text" name="end_date" class="datepicker" disabled placeholder="2014-01-01 00:00:00" style="width: 130px;" />
            now <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
            </span>
        </td>
    </tr>


</table>

<input type="submit" value="<?=$this->lang->line('create')?>" class="submit" />

</form>

<script type="text/javascript" charset="utf-8">

    $('#username').keyup(function(){
        var usr = $(this).val();
        var memberlist = usr.split(',');
        var search = memberlist[memberlist.length-1].trim();
        $.post(EE.BASE+'&C=addons_modules&M=show_module_cp&module=uri_route&method=get_memberlist', {'username': search, 'XID': EE.XID}, function(data){
            $('#member_list').html(data);
        });
    });

    $('#start_end_date_checkbox').click(function(){
        var checked = $(this).attr('checked');
        if(checked == 'checked'){
            $('#start_end_date_input').fadeTo('slow', 1);
            $('#start_end_date_input input').removeAttr('disabled');
        } else {
            $('#start_end_date_input').fadeTo('slow', 0.4);
            $('#start_end_date_input input').attr('disabled', 'disabled');

        }
    });

    $('#redirect_checkbox').click(function(){
        var checked = $(this).attr('checked');
        if(checked == 'checked'){
            $('#redirect_input').fadeTo('slow', 1);
            $('#redirect_input select').removeAttr('disabled');
        } else {
            $('#redirect_input').fadeTo('slow', 0.4);
            $('#redirect_input select').attr('disabled', 'disabled');
        }
    });

    function member(memb){
        var members = $('#username').val();
        var memberlist = members.split(',');
        memberlist[memberlist.length-1] = memberlist[memberlist.length-1].replace(memberlist[memberlist.length-1], memb).trim()
        //memberlist.push(memb);
        $('#username').val(memberlist.join(', '));
        $('#member_list').text('');
    }

    $(function() {
        $( ".datepicker" ).datepicker({dateFormat: "yy-mm-dd "+getCurrentTime()});

        function getCurrentTime() {
            var CurrentTime = "";
            try {
                var CurrentDate = new Date();
                var CurrentHours = CurrentDate.getHours();
                var CurrentMinutes = CurrentDate.getMinutes();
                var CurrentSeconds = CurrentDate.getSeconds();
                CurrentTime = "" + CurrentHours + ":" + CurrentMinutes + ":" + CurrentSeconds + "";
            }
            catch (ex) {
            }
            return CurrentTime;
        }
    });
</script>