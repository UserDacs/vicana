<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item active"><?php echo $this->lang->line('dashboard'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"><?php echo $this->lang->line('welcome_to'); ?> <?php echo $this->db->get_where('setting', array('name' => 'system_name'))->row()->content; ?> </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        
        
        
        

        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('due_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . ', ' . date('Y'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('status', 0);
                    $this->db->where('month', date('F'));
                    $this->db->where('year', date('Y'));
                    $query = $this->db->get();

                    $due_amount = $query->row()->amount;

                    echo number_format(round($due_amount > 0 ? $due_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . ', ' . date('Y'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('month', date('F'));
                    $this->db->where('year', date('Y'));
                    $query = $this->db->get();

                    $total_amount = $query->row()->amount;

                    echo number_format(round($total_amount > 0 ? $total_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('due_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F', strtotime("-1 months")))) . ', ' . date('Y', strtotime("-1 months")); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('status', 0);
                    $this->db->where('month', date('F', strtotime("-1 months")));
                    $this->db->where('year', date('Y'));
                    $query = $this->db->get();

                    $last_due_amount = $query->row()->amount;

                    echo number_format(round($last_due_amount > 0 ? $last_due_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F', strtotime("-1 months")))) . ', ' . date('Y', strtotime("-1 months")); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('month', date('F', strtotime("-1 months")));
                    $this->db->where('year', date('Y'));
                    $query = $this->db->get();

                    $last_total_amount = $query->row()->amount;

                    echo number_format(round($last_total_amount > 0 ? $last_total_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_utility_bills_overall'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('utility_bill');
                    $query = $this->db->get();

                    $overall_utility_bill = $query->row()->amount;

                    if ($overall_utility_bill > 1000000) {
                        echo number_format(round($overall_utility_bill / 1000000)) . ' M';
                    } else {
                        echo number_format(round($overall_utility_bill  > 0 ? $overall_utility_bill : 0));
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_expenses_overall'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('expense');
                    $query = $this->db->get();

                    $overall_expense = $query->row()->amount;

                    if ($overall_expense > 1000000) {
                        echo number_format(round($overall_expense / 1000000)) . ' M';
                    } else {
                        echo number_format(round($overall_expense > 0 ? $overall_expense : 0));
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_due_rents_overall'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('status', 0);
                    $query = $this->db->get();

                    $overall_due_amount = $query->row()->amount;

                    echo number_format(round($overall_due_amount > 0 ? $overall_due_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light m-b-15">
                <h5><b><?php echo $this->lang->line('total_rents_overall'); ?></b></h5>
                <p>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $query = $this->db->get();

                    $overall_amount = $query->row()->amount;

                    echo number_format(round($overall_amount > 0 ? $overall_amount : 0));
                    ?>
                </p>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->