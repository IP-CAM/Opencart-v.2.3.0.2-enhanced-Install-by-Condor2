<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th><?php echo $column_filename; ?></th>
        <th><?php echo $column_date_added; ?></th>
        <th class="text-right"><?php echo $column_action; ?></th>
      </tr>
    </thead>
    <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td><?php echo $history['filename']; ?></td>
      <td><?php echo $history['date_added']; ?></td>
      <td class="text-right"><button type="button" value="<?php echo $history['extension_install_id']; ?>" data-loading="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="3" class="text-center"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>