<?php

//show list...
//add new item to list...
//echo "show list items";
    global  $wpdb;
    $fid = $_REQUEST['fid'];
    
if (isset($_REQUEST['action'])):
    
    switch ($_REQUEST['action']):    
    case 'enable':
        $id = $_REQUEST['id'];
            $new_value = array(
                "enabled" => 1
            );
            $where = array("id" => $id);
            $format = array("%d");
            $where_format = array('%d');
            if($wpdb->update($table2, $new_value, $where, $format, $where_format)){
                $messages[] = __('Featureset successfully enabled..','wpmarketplace');
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
            }
            break;

        case 'disable':
            $id = $_REQUEST['id'];
            $new_value = array(
                "enabled" => 0
            );
            $where = array("id" => $id);
            $format = array("%d");
            $where_format = array('%d');
            if($wpdb->update($table2, $new_value, $where, $format, $where_format)){
                $messages[] = __('Featureset successfully disabled..','wpmarketplace');
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
            }
            
            break;

        case 'delete':
            $id = $_REQUEST['id'];
            $new_value = array(
                "del" => 1
            );
            $where = array("id" => $id);
            $format = array("%d");
            $where_format = array('%d');
            if($wpdb->update($table2, $new_value, $where, $format, $where_format)){
                $messages[] = __('Featureset successfully deleted..','wpmarketplace');
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
            }
            break;
   endswitch;
endif;  
    
    
    $results = $wpdb->get_results("SELECT * FROM {$table2} where fid='{$fid}' and del='0'",ARRAY_A);
    $feature_name = $wpdb->get_var(
            $wpdb->prepare("select feature_name from $table where id='%d'",
            $fid
            ));
?>

<h3>List of features for feature set : <?php echo $feature_name; ?></h3>

<?php  
        if(isset($messages) && !empty($messages)):
            foreach($messages as $key => $message):
                echo "<div class='error'>$message</div>";
            endforeach;
        endif;
?>
<div style="padding-bottom: 10px;">
<form method="post" action="edit.php?post_type=wpmarketplace&fid=<?php echo $fid; ?>&page=feature-set&list=2" style="display: inline-block;">
    <input type="hidden" name="action" value="add_new">
    <input type="submit" value="Add New Feature" class="button-primary">
</form>
<a href="edit.php?post_type=wpmarketplace&page=feature-set" class="button-secondary">Go back to list</a>
</div>

<table cellspacing="0" class="widefat fixed">
    <thead>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Sl','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Option Name','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Field Type','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('options','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Actions','wpmarketplace'); ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Sl','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Option Name','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Field Type','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('options','wpmarketplace'); ?></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __('Actions','wpmarketplace'); ?></th>
        </tr>
    </tfoot>
    
    <tbody>
        <?php 
            if($results == null && empty($results)):
        ?>
        <tr valign="top" class="alternate author-self status-inherit" id="feature-1">
            <th class="check-column" scope="row"><input type="checkbox" value="1" name="id[]"></th>
            <td colspan="4">Feature set empty..</td>
        </tr>
        <?php
        else:
            $i = 1;
            foreach($results as $key => $row):
        ?>
        <tr valign="top" class="alternate author-self status-inherit" id="feature-<?php echo $row['id']; ?>">
            <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $row['id']; ?>" name="id[]"></th>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['option_name']; ?></td>
            <td><?php echo ucfirst($row['field_type']); ?></td>
            <td>
                <?php 
                    $data = unserialize($row['option_value']);
                    $option_value = implode(' , ', $data);
                    echo $option_value;
                ?>
            </td>
            <td>
                <span style='margin-right:5px; float: left;'>
                    <form method="post" action="edit.php?post_type=wpmarketplace&page=feature-set&list=2&fid=<?php echo $fid; ?>">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" value="Edit" class="button-primary">
                    </form>
                </span>
                <span style='margin-right:5px; float: left;'>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Delete" class="button-secondary">
                    </form>
                </span>
                <span style='margin-right:5px; float: left;'>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <?php if($row['enabled']=="0"):?>
                        <input type="hidden" name="action" value="enable">
                        <input type="submit" value="Enable" class="button-secondary">
                        <?php else: ?>
                        <input type="hidden" name="action" value="disable">
                        <input type="submit" value="disable" class="button-secondary">
                        <?php endif;        ?>
                    </form>
                </span>
            </td>
        </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
    
</table>