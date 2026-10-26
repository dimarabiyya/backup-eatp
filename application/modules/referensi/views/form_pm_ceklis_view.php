<div class="box">
    <div class="title">
        <h5><?php echo lang('text_heading_title_pm_ceklis_list') ?></h5>
        <ul class="links">
            <li>
                <a href="http://localhost/pmts/index.php/referensi/referensi/pm_ceklis">List Data</a>
            </li>
            <li>
                <a href="http://localhost/pmts/index.php/referensi/referensi/add_pm_ceklis">Add Data</a>
            </li>
        </ul>
    </div>

    <?php if ($errors = validation_errors()) { ?>
        <div class="message-error" style="padding:10px">
            <?php echo $errors ?>
        </div>
    <?php } ?>

    <?php echo form_open() ?>
    <div class="form">
        <div class="fields">			

            <div class="field">
                <div class="label"><label for="ceklisId">Ceklis Id</label></div>
                <div class="input">
                    <?php echo @form_input('ceklisId', set_value('ceklisId', $pm_ceklis['ceklis_id']), 'id="ceklisId" class="medium"') ?>
                </div>
            </div>

            <div class="field">
                <div class="label"><label for="groupId">Group Id</label></div>
                <div class="input">
                    <?php echo @form_input('groupId', set_value('groupId', $pm_ceklis['group_id']), 'id="groupId" class="medium"') ?>
                </div>
            </div>

            <div class="field">
                <div class="label"><label for="descr">Descr</label></div>
                <div class="input">
                    <?php echo @form_input('descr', set_value('descr', $pm_ceklis['descr']), 'id="descr" class="medium"') ?>
                </div>
            </div>

            <div class="buttons">
                <?php echo form_submit('submit', 'Submit') ?>
            </div>

        </div>
    </div>
    <?php echo form_close() ?>

</div>