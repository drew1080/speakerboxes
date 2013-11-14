<?php
$messages = array();
if (isset($_REQUEST['action'])):
    switch ($_REQUEST['action']):
        case 'add_new':
            if(trim($_REQUEST['feature']['feature_name'])=="") {
                $messages[] = __("Feature Name field is empty...",'wpmarketplace');
                break;
            }
                
            $value = array(
                "feature_name" => $_REQUEST['feature']['feature_name'],
            );
            $format = array("%s");

            if ($wpdb->insert($table, $value, $format)){
                $messages[] = __("New Featureset added successfully..",'wpmarketplace');
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
            }
            
            break;

        case 'enable':
            $id = $_REQUEST['id'];
            $new_value = array(
                "enabled" => 1
            );
            $where = array("id" => $id);
            $format = array("%d");
            $where_format = array('%d');
            if($wpdb->update($table, $new_value, $where, $format, $where_format)){
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
            if($wpdb->update($table, $new_value, $where, $format, $where_format)){
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
            if($wpdb->update($table, $new_value, $where, $format, $where_format)){
                $messages[] = __('Featureset successfully deleted..','wpmarketplace');
            }
            else {
                $messages[] = __("Database/Query error..",'wpmarketplace');
            }
            break;

    endswitch;
endif;

$query = "SELECT * from `{$table}` where del='0'";
$results = $wpdb->get_results($query, ARRAY_A);
$wpdb->flush();
?>
    <?php  
        if(isset($messages) && !empty($messages)):
            foreach($messages as $key => $message):
                echo "<div class='error'>$message</div>";
            endforeach;
        endif;
    ?>
    <input alt="#TB_inline?height=200&amp;width=300&amp;inlineId=Popup" title="Add New Featureset" class="thickbox button-primary" type="button" value="Add New Featureset" />  
    <div id="Popup" style="display:none">
        <form method="post" action='edit.php?post_type=wpmarketplace&page=feature-set&action=add_new'>  
            <p>
                <label for='feature_name'>Feature Name: </label>
                <input type="text" name="feature[feature_name]" id='feature_name'/> 
                <input type='submit' value='Add New' class='button-primary' />
            </p>
        </form>
    </div> 
    <br> <br>
    
<table cellspacing="0" class="widefat fixed">
    <thead>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __("SL", "wpmarketplace"); ?></th>
            <th style="" class="manage-column" id="author" scope="col"><?php echo __("Feature Name", "wpmarketplace"); ?></th>
            <th style="" class="manage-columnt" id="parent" scope="col"><?php echo __("Action", "wpmarketplace"); ?></th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
            <th style="" class="manage-column" id="media" scope="col"><?php echo __("SL", "wpmarketplace"); ?></th>
            <th style="" class="manage-column" id="author" scope="col"><?php echo __("Feature Name", "wpmarketplace"); ?></th>
            <th style="" class="manage-column" id="parent" scope="col"><?php echo __("Action", "wpmarketplace"); ?></th>
        </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
        <?php
        if (!empty($results)):
            $i = 0;
            foreach ($results as $key => $row):
                ?>
                <tr valign="top" class="alternate author-self status-inherit" id="feature-<?php echo $row['id']; ?>">
                    <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $row['id']; ?>" name="id[]"></th>
                    <td class=""><?php echo ++$i; ?></td>
                    <td class=""><?php echo $row['feature_name']; ?></td>
                    <td class="">
                        <span style='margin-right:5px; float: left;'>
                            <a href="edit.php?post_type=wpmarketplace&page=feature-set&action=edit&fid=<?php echo $row['id']; ?>&list=1" class='button-primary'>Manage</a>
                        </span>
                        
                        <span style='margin-right:5px; float: left;'>
                            <?php if ($row['enabled'] == "1") { ?>
                                <a href="edit.php?post_type=wpmarketplace&page=feature-set&action=disable&id=<?php echo $row['id']; ?>" class='button-secondary'>Disable</a>
                                <?php
                            } else {
                                ?>
                                <a href="edit.php?post_type=wpmarketplace&page=feature-set&action=enable&id=<?php echo $row['id']; ?>" class='button-secondary'>Enable</a>
                            <?php } ?>
                        </span>
                        
                        <span style='margin-right:5px; float: left;'>
                            <a href="edit.php?post_type=wpmarketplace&page=feature-set&action=delete&id=<?php echo $row['id']; ?>" class='button-secondary'>Delete</a>
                        </span>

                    </td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr valign="top" class="alternate author-self status-inherit">
                <td colspan='4' scope='row'>No Feature List Found.</td>
            </tr>
        <?php
        endif;
        ?>
    </tbody> 
</table>