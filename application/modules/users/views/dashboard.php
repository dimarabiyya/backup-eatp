
Welcome <strong><?php echo get_instance()->session->userdata('user_name') ?></strong>
<p>&nbsp;</p>

<?php if (get_instance()->session->userdata('territory_name')) { ?>
( <a href="<?php echo site_url('/territory/cluster') ?>"><?php echo get_instance()->session->userdata('region_name') . ' | ' . get_instance()->session->userdata('territory_name').' )' ?></a>
<?php } else { ?>
( <a href="<?php echo site_url('territory/cluster') ?>">Pilih Territory</a> )
<?php } ?>

<p>&nbsp;</p>

