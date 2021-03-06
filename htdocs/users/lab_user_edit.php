<?php
#
# (c) C4G, Santosh Vempala, Ruban Monu and Amol Shintre
# Main page for editting lad admin account
#

include("../users/accesslist.php");
if( !(isAdmin(get_user_by_id($_SESSION['user_id'])) && in_array(basename($_SERVER['PHP_SELF']), $adminPageList)) 
     && !(isCountryDir(get_user_by_id($_SESSION['user_id'])) && in_array(basename($_SERVER['PHP_SELF']), $countryDirPageList)) 
	 && !(isSuperAdmin(get_user_by_id($_SESSION['user_id'])) && in_array(basename($_SERVER['PHP_SELF']), $superAdminPageList)) )
		header( 'Location: home.php' );
	
include("redirect.php");
include("includes/header.php");
include("includes/scripts.php");
LangUtil::setPageId("lab_config_home");

$saved_session = SessionUtil::save();

$user_id = $_REQUEST['id'];
$user = get_user_by_id($user_id);

$script_elems->enableDatePicker();
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->       
                        <h3></h3>
                        <ul class="breadcrumb">
                            <li><a href="#"><i class='icon-wrench'></i> Lab Configuration</a>
                            <span class="icon-angle-right"></span></li>
                            <li><a href="#"></a></li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
<!-- BEGIN ROW-FLUID-->   


<div class="portlet box green right_pane" id="users_div" >
        <div class="portlet-title" >
                                <h4><i class="icon-reorder"></i><?php echo "User Profile"; ?></h4>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a data-toggle="modal" class="config"></a>
                                </div>
        </div>
        
        <div class="portlet-body">
                <?php
                $tips_string = LangUtil::$pageTerms['TIPS_USERACC'];
                $page_elems->getSideTip(LangUtil::$generalTerms['TIPS'], $tips_string);
                ?>
                    <br>
                    <a href='javascript:goback();'><?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a> 
                    <br><br>
                    <?php
                    if($user == null)
                    {
                    	?>
                    	<div class='sidetip_nopos'>
                    	<?php echo LangUtil::$generalTerms['MSG_NOTFOUND']; ?>
                    	</div>
                    	<?php
                    	include("includes/footer.php");
                    	return;
                    }
                    ?>
                    <form name='user_ops' action='ajax/lab_user_update.php' method='post'>
                    	<table id='form_table'>
                    		<tr>
                    			<td><?php echo LangUtil::$generalTerms['USERNAME']; ?></td>
                    			<td>
                    				<?php echo $user->username; ?>
                    				<input type='hidden' name='username' id='username' value="<?php echo $user->username; ?>" class='uniform_width'></input>
                    			</td>
                    		</tr>
                    		
                    		<tr>
                    			<td><?php echo LangUtil::$generalTerms['NAME'] ?></td>
                    			<td><input type="text" name="fullname" id="fullname" value="<?php echo $user->actualName; ?>" class='uniform_width' /><br></td>
                    		</tr>
                    		<tr>
                    			<td><?php echo LangUtil::$generalTerms['EMAIL'] ?></td>
                    			<td><input type="text" name="email" id="email" value="<?php echo $user->email; ?>" class='uniform_width' /><br></td>
                    		</tr>
                    		<tr>
                    			<td><?php echo LangUtil::$generalTerms['PHONE'] ?>&nbsp;&nbsp;&nbsp;</td>
                    			<td><input type="text" name="phone" id="phone" value="<?php echo $user->phone; ?>" class='uniform_width' /><br></td>
                    		</tr>
                    		<tr>
                    			<td><?php echo LangUtil::$generalTerms['TYPE'] ?></td>
                    			<td>
                    			<select name='level' id='level' class='uniform_width'>
                    			<?php
                    			$page_elems->getLabUserTypeOptions($user->level);
                    			?>
                    			</select>
                    			</td>
                    		</tr>
                    		<tr>
                                    <td><?php echo LangUtil::$generalTerms['LAB_SECTION']; ?></td>
                                    <td>
                                        <select name="cat_code12[]" id="labsec" class="medium m-wrap tooltip-examples" rows="3" multiple="multiple" data-toggle="tooltip" data-original-title="Use CTRL or SHIFT to select multiple lab sections">
                                            
                                            <?php $page_elems->getTestCategorySelectOption(); ?>
                                        </select>
                                        <td>
                                        <input type="checkbox" id="mrk_all" onclick="javascript:selectDeselect();">Mark All</input>
                                        </td>
                                    </td>
                                </tr>
                    		<tr>
                    			<td>Can verify results?&nbsp;&nbsp;&nbsp;</td>
                    			<td>
                    				<input type="checkbox" name="canverify" id="verify"
                    				<?php
                    				if($user->canverify == 1)
                    					echo "checked ";
                    				?>
                    				/>
                    			</td>
                    		</tr>
                    		<tr valign='top'>
                    			<td><?php echo LangUtil::$pageTerms['USE_PNAME_RESULTS']; ?>?</td>
                    			<td>
                    				<input type="checkbox" name="showpname" id="showpname" <?php
                    				if($user->level == $LIS_TECH_SHOWPNAME)
                    					echo "checked ";
                    				?>/><?php echo LangUtil::$generalTerms['YES']; ?>
                    			</td>
                    		</tr>
                    		<tr>
                    			<td>
                    				<a href="javascript:toggle_and_clear();"><?php echo LangUtil::$generalTerms['PWD_RESET']; ?></a>
                    				<?php $page_elems->getSmallArrow(); ?>
                    				&nbsp;&nbsp;&nbsp;&nbsp;
                    			</td>
                    			<td><span id='password_row' style='display:none'><input name='pwd' id='pwd' type='text' class='uniform_width' /><span></td>
                    		</tr>
                    		<tr>
                    			<td></td>
                    			<td>
                    				<br>
                    				<input class="btn blue" value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT'] ?>' type='button' onclick="javascript:update_lab_user();" />
                    				&nbsp;&nbsp;&nbsp;
                    				<input class="btn" value='<?php echo LangUtil::$generalTerms['CMD_CANCEL'] ?>' type='button' onclick="javascript:goback();" />
                    				&nbsp;&nbsp;&nbsp;
                    				<span id='edit_user_progress' style='display:none'>
                    				<?php
                    					$page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']);
                    				?>
                    				</span>
                    			</td>
                    		</tr>
                    		<tr>
                    			<td></td>
                    			<td>
                    				<span id='error_msg' class='error_string'>
                    				</span>
                    			</td>
                    		</tr>
                    	</table>
                    </form>
                </div>
            </div>
<script type='text/javascript'>
function selectDeselect() {
    var select_all = $('#mrk_all').prop('checked') ? true : false;
    var select = $('#labsec');
    $('option', select).prop('selected', select_all);
}
function toggle_and_clear(div_id)
{
    $('#password_row').toggle();
    $('#pwd').attr("value", "");
}

function goback()
{
    window.location="<?php echo $_REQUEST['backurl']; ?>&show_u=1";
}

function update_lab_user()
{

    // Begin email address test
    var email_regex = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i);

    if (!(email_regex.test($('#email').attr("value"))) && $('#email').attr("value") != '') {
        alert("Invalid email supplied.  Please enter an email in the form abcd@efgh.ijk or leave the field blank.");
        return;
    }
    // End email address test

    var username = $('#username').val();
    var pwd = $('#pwd').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var fullname = $('#fullname').val();
    var level = $('#level').val();
    var lang_id = "default";
    var url_string = 'ajax/lab_user_update.php';
    var showpname = 0;
    
    var cat_code12 = [];
	$('#labsec :selected').each(function(i, selected){
	cat_code12[i] = $(selected).val();
	});
    if($('#showpname').is(":checked"))
    {
        showpname = 1;
    }
     var canverify = 0;
    if($('#verify').is(":checked"))
    {
        canverify = 1;
    }
    var data_string = 'id=<?php echo $user_id; ?>&un='+username+'&p='+pwd+'&fn='+fullname+'&em='+email+'&cat='+cat_code12+'&ph='+phone+'&lev='+level+'&lang='+lang_id+"&showpname="+showpname+"&verify="+canverify;
    $('#edit_user_progress').show();
    $.ajax({
        type: "POST",
        url: url_string,
        data: data_string,
        success: function(msg) {
            $('#edit_user_progress').hide();
            window.location = "<?php echo $_REQUEST['backurl']; ?>&show_u=1&aupdate=<?php echo $user->username; ?>";
        }
    });
}

$(document).ready(function(){
    $('#lang_id').attr("value", "<?php echo $user->langId; ?>");

if ($("#level option:selected").val()==<?php echo $LIS_CLERK;?>){
    $('#verify').attr("disabled","true");}

    $('#phone').keypress(function(event) {
    var code = (event.keyCode ? event.keyCode : event.which);
    if (!(
            (code >= 48 && code <= 57) // "[0-9]"
            || (code == 46) // "."
            || (code == 45) // "-"
            || (code == 40) // ")"
            || (code == 41) // "("
            || (code == 32) // " "
        ))
        event.preventDefault();
    });
});
//disabling can verify result if user level is receptionist
$('#level').change(function() {
   
    if ($("#level option:selected").val()==<?php echo $LIS_CLERK;?>){
    $('#verify').attr("disabled","true");}
    else if ($("#level option:selected").val()!=<?php echo $LIS_CLERK;?>){
    $('#verify').removeAttr("disabled");
    }
    
});


</script>

<?php 
SessionUtil::restore($saved_session);
include("includes/footer.php"); 
?>
