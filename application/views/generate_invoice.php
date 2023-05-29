<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('generate_invoice'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('generate_invoice_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-6 offset-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <ul class="nav nav-tabs nav-tabs-inverse nav-justified nav-justified-mobile">
                    <li class="nav-item">
                        <a href="#date-range" data-toggle="tab" class="nav-link active">
                            <span class="d-md-inline"><?php echo $this->lang->line('date_range'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#multiple-months" data-toggle="tab" class="nav-link">
                            <span class="d-md-inline"><?php echo $this->lang->line('multiple_months'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#single-month" data-toggle="tab" class="nav-link">
                            <span class="d-md-inline"><?php echo $this->lang->line('single_month'); ?></span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="date-range">
                        <?php echo form_open('generate_invoice/range', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="tenant_id">
                                    <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                                    <?php
                                    $tenants = $this->db->get_where('tenant', array('status' => 1))->result_array();
                                    foreach ($tenants as $tenant) :
                                    ?>
                                        <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('status'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status">
                                    <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                                    <option value="0"><?php echo $this->lang->line('due'); ?></option>
                                    <option value="1"><?php echo $this->lang->line('paid'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('range'); ?> *</label>
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" name="start" placeholder="<?php echo $this->lang->line('date_start'); ?>" data-parsley-required="true" />
                                <span class="input-group-addon"><?php echo $this->lang->line('to'); ?></span>
                                <input type="text" class="form-control" name="end" placeholder="<?php echo $this->lang->line('date_end'); ?>" data-parsley-required="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-default" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_time_period_invoice'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="tab-pane fade" id="multiple-months">
                        <?php echo form_open('generate_invoice/multiple', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="tenant_id">
                                    <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                                    <?php foreach ($tenants as $tenant) : ?>
                                        <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('status'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status">
                                    <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                                    <option value="0"><?php echo $this->lang->line('due'); ?></option>
                                    <option value="1"><?php echo $this->lang->line('paid'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('year'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="year">
                                    <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                    <option value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                    <option value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                    <option value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                    <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                    <option value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                    <option value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                    <option value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('months'); ?> *</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="January" value="January" name="months[]" data-parsley-required="true" />
                                        <label for="January"><?php echo $this->lang->line('january'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="May" value="May" name="months[]" />
                                        <label for="May"><?php echo $this->lang->line('may'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="September" value="September" name="months[]" />
                                        <label for="September"><?php echo $this->lang->line('september'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="February" value="February" name="months[]" />
                                        <label for="February"><?php echo $this->lang->line('february'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="June" value="June" name="months[]" />
                                        <label for="June"><?php echo $this->lang->line('june'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="October" value="October" name="months[]" />
                                        <label for="October"><?php echo $this->lang->line('october'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="March" value="March" name="months[]" />
                                        <label for="March"><?php echo $this->lang->line('march'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="July" value="July" name="months[]" />
                                        <label for="July"><?php echo $this->lang->line('july'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="November" value="November" name="months[]" />
                                        <label for="November"><?php echo $this->lang->line('november'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="April" value="April" name="months[]" />
                                        <label for="April"><?php echo $this->lang->line('april'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="August" value="August" name="months[]" />
                                        <label for="August"><?php echo $this->lang->line('august'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="December" value="December" name="months[]" />
                                        <label for="December"><?php echo $this->lang->line('december'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-inline" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_single_tenant_invoices'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="tab-pane fade" id="single-month">
                        <?php echo form_open('generate_invoice/single', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select class="multiple-select2 form-control" multiple="multiple" name="tenants[]" data-parsley-required="true" style="width: 100%">
                                    <option value="All"><?php echo $this->lang->line('all_tenants'); ?></option>
                                    <?php foreach ($tenants as $tenant) : ?>
                                        <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('status'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status">
                                    <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                                    <option value="0"><?php echo $this->lang->line('due'); ?></option>
                                    <option value="1"><?php echo $this->lang->line('paid'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('year'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="year">
                                    <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                    <option value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                    <option value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                    <option value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                    <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                    <option value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                    <option value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                    <option value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('month'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="month">
                                    <option value=""><?php echo $this->lang->line('select_month'); ?></option>
                                    <option value="January"><?php echo $this->lang->line('january'); ?></option>
                                    <option value="February"><?php echo $this->lang->line('february'); ?></option>
                                    <option value="March"><?php echo $this->lang->line('march'); ?></option>
                                    <option value="April"><?php echo $this->lang->line('april'); ?></option>
                                    <option value="May"><?php echo $this->lang->line('may'); ?></option>
                                    <option value="June"><?php echo $this->lang->line('june'); ?></option>
                                    <option value="July"><?php echo $this->lang->line('july'); ?></option>
                                    <option value="August"><?php echo $this->lang->line('august'); ?></option>
                                    <option value="September"><?php echo $this->lang->line('september'); ?></option>
                                    <option value="October"><?php echo $this->lang->line('october'); ?></option>
                                    <option value="November"><?php echo $this->lang->line('november'); ?></option>
                                    <option value="December"><?php echo $this->lang->line('december'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-autoClose" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_monthly_invoices'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
