<script type="text/javascript" charset="utf-8">
            /* Table initialisation */
            jQuery(document).ready(function() {
                
                jQuery.extend( jQuery.fn.dataTableExt.oStdClasses, {
                    "sSortAsc": "header headerSortDown",
                    "sSortDesc": "header headerSortUp",
                    "sSortable": "header"
                } );
                /* API method to get paging information */
                            jQuery.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
                            {
                                return {
                                    "iStart":         oSettings._iDisplayStart,
                                    "iEnd":           oSettings.fnDisplayEnd(),
                                    "iLength":        oSettings._iDisplayLength,
                                    "iTotal":         oSettings.fnRecordsTotal(),
                                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                                    "iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
                                    "iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
                                };
                            }
/* Bootstrap style pagination control */
            jQuery.extend( jQuery.fn.dataTableExt.oPagination, {
                "bootstrap": {
                    "fnInit": function( oSettings, nPaging, fnDraw ) {
                        var oLang = oSettings.oLanguage.oPaginate;
                        var fnClickHandler = function ( e ) {
                            if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                                fnDraw( oSettings );
                            }
                        };

                        jQuery(nPaging).addClass('pagination').append(
                            '<ul>'+
                                '<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
                                '<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
                            '</ul>'
                        );
                        var els = jQuery('a', nPaging);
                        jQuery(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
                        jQuery(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
                    },

                    "fnUpdate": function ( oSettings, fnDraw ) {
                        var oPaging = oSettings.oInstance.fnPagingInfo();
                        var an = oSettings.aanFeatures.p;
                        var i, sClass, iStart, iEnd, iHalf=Math.floor(oPaging.iTotalPages/2);

                        if ( oPaging.iTotalPages < 5) {
                            iStart = 1;
                            iEnd = oPaging.iTotalPages;
                        }
                        else if ( oPaging.iPage <= iHalf ) {
                            iStart = 1;
                            iEnd = 5;
                        } else if ( oPaging.iPage >= (5-iHalf) ) {
                            iStart = oPaging.iTotalPages - 5 + 1;
                            iEnd = oPaging.iTotalPages;
                        } else {
                            iStart = oPaging.iPage - Math.ceil(5/2) + 1;
                            iEnd = iStart + 5 - 1;
                        }

                        for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
                            // Remove the middle elements
                            jQuery('li:gt(0)', an[i]).filter(':not(:last)').remove();

                            // Add the new list items and their event handlers
                            for ( i=iStart ; i<=iEnd ; i++ ) {
                                sClass = (i==oPaging.iPage+1) ? 'class="active"' : '';
                                jQuery('<li '+sClass+'><a href="#">'+i+'</a></li>')
                                    .insertBefore('li:last', an[i])
                                    .bind('click', function () {
                                        oSettings._iDisplayStart = (parseInt(jQuery('a', this).text(),10)-1) * oPaging.iLength;
                                        fnDraw( oSettings );
                                    } );
                            }

                            // Add / remove disabled classes from the static elements
                            if ( oPaging.iPage === 0 ) {
                                jQuery('li:first', an[i]).addClass('disabled');
                            } else {
                                jQuery('li:first', an[i]).removeClass('disabled');
                            }

                            if ( oPaging.iPage === oPaging.iTotalPages-1 ) {
                                jQuery('li:last', an[i]).addClass('disabled');
                            } else {
                                jQuery('li:last', an[i]).removeClass('disabled');
                            }
                        }

                    }
                }
            } );
            jQuery('#my_products').dataTable( {
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "sPaginationType": "bootstrap"
                } );
            } );
        </script>
<style type="text/css">
div.dataTables_length label {
    width: 460px;
    float: left;
    text-align: left;
}
 
div.dataTables_length select {
    width: 75px;
}
 
div.dataTables_filter label {
    float: right;
    /*width: 460px;*/
}
 
div.dataTables_info {
    padding-top: 8px;
}
 
div.dataTables_paginate {
    float: right;
    margin: 0;
}
 
table {
    margin: 1em 0;
    clear: both;
}
</style>
    
<div class="container-fluid">
<table width="100%" class="table table-bordered table-striped" id="my_products">
<thead>
<tr>
<th width="80%"><?php echo __("Title","wpmarketplace");?></th>
<th><?php echo __("Status","wpmarketplace");?></th>
<th><?php echo __("Action","wpmarketplace");?></th>
</tr>
</thead>
<tbody>
<?php
global $current_user, $wpdb;
get_currentuserinfo();
$sap = get_option('permalink_structure')?'?':'&';

$products = $wpdb->get_results("select * from {$wpdb->prefix}posts where post_type='wpmarketplace' and (post_status='publish' or post_status='draft' or post_status='pending') and post_author='{$current_user->ID}'");
if(count($products)>0){
$counter=0;     
foreach($products as $product){
$counter++;
if(($counter%2)!=0)$oddeven="odd";
else $oddeven= "even";
//$permalink = get_permalink( $product->ID );
?>
<tr class="<?php echo $oddeven;?> gradeA">
<td>
<a href="<?php echo get_permalink().$sap; ?>section=wpmp-add-product&product=<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></a>
</td>
<td>
<?php echo $product->post_status; ?>
</td>
<td>
<nobr>                             
<a class="btn btn-mini btn-info" href="<?php echo get_permalink().$sap; ?>section=wpmp-add-product&product=<?php echo $product->ID; ?>">Edit</a>
<a class="btn btn-mini btn-danger delpro" onclick="return confirm('Are you sure?');" href="<?php echo get_permalink().$sap; ?>dproduct=<?php echo $product->ID; ?>">Delete</a>
</nobr>
</td>
</tr> 
<?php    
}} else{
?>
<tr><td><?php echo __("You didn't add any product yet!","wpmarketplace");?></td><td></td><td></td></tr>
<?php    
}
?>
</tbody>
</table>
</div>
 
 
