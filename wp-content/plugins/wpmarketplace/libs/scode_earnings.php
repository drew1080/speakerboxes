<?php
global $wpdb, $current_user;
$uid = $current_user->ID;
$sql = "select p.*,i.price,i.quantity, o.date from {$wpdb->prefix}mp_orders o,
                      {$wpdb->prefix}mp_order_items i,
                      {$wpdb->prefix}posts p 
                      where p.post_author=$uid and 
                            i.oid=o.order_id and 
                            i.pid=p.ID and
                            i.quantity > 0 and
                            o.payment_status='Completed' order by o.date desc";

$sales = $wpdb->get_results($sql);   
$sql = "select sum(i.price*i.quantity) from {$wpdb->prefix}mp_orders o,
                      {$wpdb->prefix}mp_order_items i,
                      {$wpdb->prefix}posts p 
                      where p.post_author=$uid and 
                            i.oid=o.order_id and 
                            i.pid=p.ID and 
                            i.quantity > 0 and
                            o.payment_status='Completed'";

 $total_sales= $wpdb->get_var($sql);
 $commission = wpmp_site_commission();
 $total_commission = $total_sales*$commission/100;
 $total_earning = $total_sales - $total_commission;

 $sql = "select sum(amount) from {$wpdb->prefix}mp_withdraws 
                      where uid=$uid";

$total_withdraws = $wpdb->get_var($sql); 

$balance = $total_earning-$total_withdraws;

//finding matured balance 
$payout_duration=get_option("wpmp_payout_duration");
$sqlm = "select sum(i.price*i.quantity) from {$wpdb->prefix}mp_orders o,
                      {$wpdb->prefix}mp_order_items i,
                      {$wpdb->prefix}posts p 
                      where p.post_author=$uid and 
                            i.oid=o.order_id and 
                            i.pid=p.ID and
                            i.quantity > 0 and
                            o.payment_status='Completed'
                            and (o.date+({$payout_duration}*24*60*60))<".time()."
                            ";

$tempbalance = $wpdb->get_var($sqlm);
$tempbalance = $tempbalance - ($tempbalance*$commission/100);
$matured_balance=$tempbalance-$total_withdraws;
/**********************************/

//finding pending balance
$pending_balance=$balance-$matured_balance;

                                                  
if(get_user_meta($uid,'w_req',true)==1) { delete_user_meta($uid,'w_req');


?>
<blockquote class="success"><b>Withdraw request</b><br/>Withdraw request submitted successfully.</blockquote>
<?php } ?>
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
            jQuery('#earnings').dataTable( {
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "sPaginationType": "bootstrap"
                } );
            } );
        </script>
<style type="text/css">
div.dataTables_length label {
   /* width: 460px;*/
    float: left;
    text-align: left;
}
 
div.dataTables_length select {
    width: 75px;
}
 
div.dataTables_filter label {
    float: right;
   /* width: 460px;*/
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
.center{
    text-align: center;
}
.table th,
.table td{
    font-size: 10pt;
}
</style>
<div class="container-fluid">
<div class="well well-small">
<div class="row-fluid" style="height: 25px;">
    <span class="span2 center"><b style="color: #336699;font-size:10pt"><?php echo __("Sales:","wpmarketplace");?> $<?php echo number_format($total_sales,2); ?></b></span>
    <span class="span2 center" title="After <?php echo $commission ?>% Site Commission Deducted"><b style="color: #336699;font-size:10pt"><?php echo __("Earning:","wpmarketplace");?> $<?php echo number_format($total_earning,2); ?></b></span>
    <span class="span2 center"><b style="color: #366DDB;font-size:10pt"><?php echo __("Withdraws:","wpmarketplace");?> $<?php echo number_format($total_withdraws,2); ?></b></span>
    <span class="span2 center"><b style="color: #008000;font-size:10pt"><?php echo __("Pending:","wpmarketplace");?> $<?php echo number_format($pending_balance,2);?></b></span>
    <span class="span4 center"><b class="span5" style="color: #008000;font-size:10pt"><?php echo __("Balance:","wpmarketplace");?> $<?php echo number_format($matured_balance,2); ?> </b><form action="" method="post" class="span5 pull-right"><input type="hidden" name="withdraw" value="1"><input type="hidden" name="withdraw_amount" value="<?php echo $matured_balance;?>"><button <?php if($matured_balance<=0){?>disabled="disabled" <?php } ?>  class="btn btn-success btn-small pull-right" type="submit"><?php echo __("Withdraw","wpmarketplace");?></button></form></span>
</div>
</div>
</div>
<table class="table table-bordered table-striped" id="earnings">
<thead>
<tr><th><?php echo __("Date","wpmarketplace");?></th><th><?php echo __("Item","wpmarketplace");?></th><th><?php echo __("Quantity","wpmarketplace");?></th><th><?php echo __("Price","wpmarketplace");?></th><th><?php echo __("Commission","wpmarketplace");?></th><th><?php echo __("Earning","wpmarketplace");?></th></tr>
</thead>
<tbody>
<?php foreach($sales as $sale){ $sale->site_commission = $sale->site_commission?$sale->site_commission:$sale->price*$commission/100; ?>
<tr><td><?php echo date("Y-m-d H:i",$sale->date); ?></td><td><?php echo $sale->post_title; ?></td><td><?php echo $sale->quantity; ?></td><td><?php echo get_option('_wpmp_curr_sign','$').number_format($sale->price,2); ?></td><td><?php echo get_option('_wpmp_curr_sign','$').number_format($sale->site_commission,2); ?></td><td><?php echo get_option('_wpmp_curr_sign','$').number_format($sale->price-$sale->site_commission,2); ?></td></tr>
<?php } ?>

</tbody>
 <tfoot>
    <tr><th colspan="3"> </th><th><?php echo get_option('_wpmp_curr_sign','$').number_format($total_sales,2); ?></th><th><?php echo get_option('_wpmp_curr_sign','$').number_format($total_commission,2); ?></th><th><?php echo get_option('_wpmp_curr_sign','$').number_format($total_earning,2); ?></th></tr>
    </tfoot>
</table>