<?php
$messages = array();
//$wpdb->show_errors();
$add_error = (int)0;
$edit_error = 0;
$fid = $_REQUEST['fid'];


if (isset($_REQUEST['action'])):
    switch ($_REQUEST['action']):
        case 'add_new_item':
            
            if(trim($_REQUEST['featureset']['option_name'])=="") {
                $messages[] = __("Option Name field is Empty",'wpmarketplace');
                $add_error = 1;
                //break;
            }
            if(trim($_REQUEST['featureset']['field_type'])=="") {
                $messages[] = __("Field Type field is Empty",'wpmarketplace');
                $add_error = 1;
                //break;
            }
            if(trim($_REQUEST['featureset']['option_value'])=="") {
                $messages[] = __("Options Filed is Empty",'wpmarketplace');
                $add_error = 1;
                //break;
            }
            if($add_error == 1){
                $row['option_name'] = $_REQUEST['featureset']['option_name'];
                $row['field_type'] = $_REQUEST['featureset']['field_type'];
                $row['option_value']= $_REQUEST['featureset']['option_value'];
                        
            break;
            }
            $option_value = explode('\n', trim($_REQUEST['featureset']['option_value']));
            
            $value = array(
                "fid" => trim($_REQUEST['fid']),
                "option_name" => trim($_REQUEST['featureset']['option_name']),
                "field_type"  => trim($_REQUEST['featureset']['field_type']),
                "option_value" => serialize($option_value),
            );
            $format = array("%d","%s","%s","%s");

            if ($wpdb->insert($table2, $value, $format)){
                $messages[] = __("New Item added successfully..",'wpmarketplace');
                echo "<script>window.location='edit.php?post_type=wpmarketplace&page=feature-set&list=1&fid=$fid';</script>";
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
                $add_error = 1;
            }
            
            break;
            
        case 'add_new':
            
            $row['option_name'] = isset($_REQUEST['featureset']['option_name'])?$_REQUEST['featureset']['option_name']:"";
            $row['field_type'] = isset($_REQUEST['featureset']['field_type'])?$_REQUEST['featureset']['field_type']:"";;
            $row['option_value']= isset($_REQUEST['featureset']['option_value'])?$_REQUEST['featureset']['option_value']:"";;
            break;
        
        case 'edit':
            
            $id = $_REQUEST['id'];
            $query = "select * from $table2 where id='$id'";
            $row = $wpdb->get_row($query,ARRAY_A);
            if($row == null || empty($row)){
                $row['option_name'] = "";
                $row['field_type'] = "";
                $row['option_value']= "";
            }
            else {
                $data = unserialize($row['option_value']);
                $option_value = implode("\n", $data);
                $row['option_value'] = $option_value;
            }
            
            break;
            
        case 'edit_item':
            
            $id = $_REQUEST['id'];
            if(trim($_REQUEST['featureset']['option_name'])=="") {
                $messages[] = __("Option Name field is Empty",'wpmarketplace');
                $edit_error = 1;
                //break;
            }
            if(trim($_REQUEST['featureset']['field_type'])=="") {
                $messages[] = __("Field Type field is Empty",'wpmarketplace');
                $edit_error = 1;
                //break;
            }
            if(trim($_REQUEST['featureset']['option_value'])=="") {
                $messages[] = __("Options Filed is Empty",'wpmarketplace');
                $edit_error = 1;
                //break;
            }
            
            if($edit_error == 1){
                $row['option_name'] = $_REQUEST['featureset']['option_name'];
                $row['field_type'] = $_REQUEST['featureset']['field_type'];
                $row['option_value']= $_REQUEST['featureset']['option_value'];
                        
            break;
            }
            
            $option_value = explode('\n', trim($_REQUEST['featureset']['option_value']));
            
            $new_value = array(
                "option_name" => trim($_REQUEST['featureset']['option_name']),
                "field_type"  => trim($_REQUEST['featureset']['field_type']),
                "option_value" => serialize($option_value),
            );
            $where = array("id" => $id);
            $format_where = array("%d");
            $format_value = array("%s","%s","%s");
            if($wpdb->update($table2, $new_value, $where, $format_value, $format_where)){
                $messages[] = __('Featureset successfully enabled..','wpmarketplace');
                echo "<script>window.location='edit.php?post_type=wpmarketplace&page=feature-set&list=1&fid=$fid';</script>";
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
                $edit_error = 1;
            }
    endswitch;
endif;
//$wpdb->print_error();
//$query = "SELECT * from `{$table}` where del='0'";
//$results = $wpdb->get_results($query, ARRAY_A);
$wpdb->flush();
?>
    <?php  
        if(isset($messages) && !empty($messages)):
            foreach($messages as $key => $message):
                echo "<div class='error'>$message</div>";
            endforeach;
        endif;
    ?>


<form method='post'>
    <?php 
        if((isset($_REQUEST['action']) && $_REQUEST['action']=="add_new") || $add_error==1){
            echo "<input type='hidden' name='action' value='add_new_item' />";
        }
        elseif((isset($_REQUEST['action']) && $_REQUEST['action']=="edit") || $edit_error==1){
            echo "<input type='hidden' name='action' value='edit_item' />";
            echo "<input type='hidden' name='id' value='$id' />";
        }
    ?>
    <table class='form-table'>
        <tr valign='top'>
            <td><label for='feature_name'>Feature Name: </label></td>
            <td>
                <input type='text' name='featureset[option_name]' value='<?php echo $row['option_name']?>' class="regular-text" id="feature_name"/>
                <br><span class="description">Enter Feature Name</span>
            </td>
        </tr>
        <tr valign='top'>
            <td><label for='field_type'>Field Type: </label></td>
            <td>
                <select name='featureset[field_type]' id='field_type' class="select">
                    <option value="">Select Field Type</option>
                    <option value='text' <?php if($row['field_type']=="text") { echo 'selected="selected"';}  ?>>Text</option>
                    <option value='dropdown' <?php if($row['field_type']=="dropdown") { echo 'selected="selected"';}  ?>>Dropdown</option>
                    <option value='checkbox' <?php if($row['field_type']=="checkbox") { echo 'selected="selected"';}  ?>>Checkbox</option>
                    <option value='radio' <?php if($row['field_type']=="radio") { echo 'selected="selected"';}  ?>>Radio</option>
                </select>
                <br><span class="description">Select Field Type</span>
            </td>
        </tr>
        <tr valign='top'>
            <td>Options:</td>
            <td valign="top">
                <textarea name="featureset[option_value]" class="" rows="5" cols="55"><?php echo $row['option_value']; ?></textarea>
                <br><span class="description">One feature in one line</span>
            </td>
        </tr>
        <tr valign='top'>
            <td>
                <input type='submit' value='Save' class='button-primary' />
                <a class="button-secondary" href="edit.php?post_type=wpmarketplace&page=feature-set&list=1&fid=<?php echo $fid; ?>">Go back to list</a>
            </td>
        </tr>
    </table>
</form>
