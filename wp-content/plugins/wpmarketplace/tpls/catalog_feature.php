<div class="wp-marketplace">
<div id="catalog" class="row-fluid">

<?php

global $wpdb, $wp_query;
$postsperpage = 15;
$imgwidth = 225;
if(isset($params['perpage'])&&$params['perpage']>0) $postsperpage = $params['perpage'];
if(isset($params['imgwidth'])&&$params['imgwidth']>0) $imgwidth = $params['imgwidth'];

$featured_products=$wpdb->get_results("select * from {$wpdb->prefix}mp_feature_products fp inner join {$wpdb->prefix}posts p on p.ID=fp.productid where p.post_type='wpmarketplace' ");

foreach($featured_products as $key => $value):
query_posts( 'post_type=wpmarketplace&posts_per_page=1&p=' .$value->ID);

if(have_posts()):
    while(have_posts()): the_post();
    $cols = 3;
?>

    <div class="pin">
        <div class="thumb">
        <?php the_post_thumbnail(array($imgwidth,($imgwidth*1.5))); ?>
        </div>
        <div class="thumb-info">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="pull-left">
        <?php if(function_exists('wpmp_product_price')) echo get_option('_wpmp_curr_sign','$').wpmp_product_price(get_the_ID()); ?>
        </div>
        <div class="pull-right">
            <?php if(function_exists('wpmp_addtocart_link')) echo wpmp_addtocart_link(get_the_ID()); ?>
        </div>
        <div style="clear: both"></div>
        </div>
    </div>

    <?php
    endwhile;
endif;

wp_reset_query();

endforeach;
?>
    </div>

</div>

<style>
    #catalog .pin{
       margin: 0 15px 15px 0;
       background: #eeeeee;
       border-radius: 3px;
       padding: 5px;
       width: <?php echo $imgwidth; ?>px;
    }
    #catalog .pin:nth-child(4n+1){
        margin-left: 0 !important;
    }
    .pin h3{
        font-size:10pt;
        font-weight: normal;
        margin: 0;
        line-height: normal;
        margin-bottom: 10px;
    }
    .thumb-info{
        padding: 5px;
    }
</style>

<script>
    jQuery('#catalog').freetile();
</script>