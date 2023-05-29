<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('add_room'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('add_room_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-6 offset-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <?php echo form_open('rooms/add', array('method' => 'post', 'data-parsley-validate' => 'ture')); ?>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('room_number_or_name'); ?> *</label>
                        <input type="text" name="room_number" placeholder="<?php echo $this->lang->line('room_number_or_name_placeholder'); ?>" data-parsley-required="true" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('daily_rent'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>) *</label>
                        <input type="text" data-parsley-required="true" data-parsley-type="number" name="daily_rent" placeholder="<?php echo $this->lang->line('daily_rent_placeholder'); ?>" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('monthly_rent'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>) *</label>
                        <input type="text" data-parsley-required="true" data-parsley-type="number" name="monthly_rent" placeholder="<?php echo $this->lang->line('monthly_rent_placeholder'); ?>" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('floor_number'); ?></label>
                        <input type="text" name="floor" placeholder="<?php echo $this->lang->line('floor_number_placeholder'); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('remarks'); ?></label>
                        <textarea style="resize: none" type="text" name="remarks" placeholder="<?php echo $this->lang->line('enter_remarks'); ?>" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
                    <?php echo form_close(); ?>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->