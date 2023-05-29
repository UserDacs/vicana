<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding-top: 30px;
            /* border: 1px solid #eee; */
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(4) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 24px;
            line-height: 24px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(4) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(4) {
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    $tenant_id = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_id;
    $invoice_type = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_type;
    $tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();

    $invoice_total = 0;
    ?>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <?php echo $this->db->get_where('setting', array('name' => 'tagline'))->row()->content; ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php echo $this->lang->line('invoice'); ?> #: <?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number; ?><br>
                                <?php echo $this->lang->line('created_on'); ?>: <?php echo date('F d, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->created_on); ?><br>
                                <?php echo $this->lang->line('due'); ?>: <?php echo date('F d, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->due_date); ?><br>
                                <?php echo $this->lang->line('status'); ?>: <?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->status ? $this->lang->line('paid') : $this->lang->line('due'); ?><br>
                                <?php if ($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->payment_method_id): ?>
                                <?php 
                                    $payment_method_query  =   $this->db->get_where('payment_method', array('payment_method_id' => $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->payment_method_id));
                                    if ($payment_method_query->num_rows() > 0):
                                ?>
                                <?php echo $this->lang->line('payment_method')?>: <?php $payment_method_query->row()->name; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php echo $this->lang->line('late_fee'); ?>: <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee); ?>
                                <?php $late_fee = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?><br>
                                <?php echo $this->db->get_where('setting', array('name' => 'address'))->row()->content; ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_name; ?><br>
                                <?php 
                                $address_query = $this->db->get_where('tenant', array('tenant_id' => $tenant_id));
                                if ($address_query->num_rows() > 0) {
                                    if ($address_query->row()->work_address) {
                                        echo $address_query->row()->work_address;
                                    } else {
                                        echo 'Address not found.';
                                    }
                                } else {
                                    echo 'Tenant not found.';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td><?php echo $this->lang->line('description'); ?></td>
                <?php if ($invoice_type == 0) : ?>
                <td><?php echo $this->lang->line('starting_date'); ?></td>
                <td><?php echo $this->lang->line('ending_date'); ?></td>
                <?php else : ?>
                <td><?php echo $this->lang->line('month'); ?></td>
                <td><?php echo $this->lang->line('year'); ?></td>
                <?php endif; ?>
                <td><?php echo $this->lang->line('row_total'); ?></td>
            </tr>
            <?php if ($invoice_type == 0) : ?>
            <!-- Starts if invoice type is Date range -->
            <tr class="item">
                <td><?php echo $this->lang->line('date_range_rent'); ?></td>
                <td><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->start_date); ?></td>
                <td><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->end_date); ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('invoice_id', $invoice_id);
                    $query = $this->db->get();

                    $invoice_total += $query->row()->amount;

                    echo number_format($query->row()->amount);
                    ?>
                </td>
            </tr>
            <?php
                $invoice_services_total = 0;
                $invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_services as $invoice_service):
                    if ($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->num_rows() > 0):
            ?>
            <tr>
                <td><span class="text-inverse"><?php echo $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->name; ?></span></td>
                <td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
                <td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
                <td class="text-right">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost); ?>
                    <?php $invoice_services_total += $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost; ?>
                </td>
            </tr>
            <?php 
                    endif;
                endforeach; 
            ?>
            <!-- Ends if invoice type is Date range -->
            <?php else : ?>
            <!-- Starts if invoice type is Multiple months or Single Month -->
            <?php foreach ($tenant_rents as $tenant_rent) : ?>
            <tr class="item">
                <td><?php echo $this->lang->line('monthly_rent'); ?></td>
                <td><?php echo $tenant_rent['month']; ?></td>
                <td><?php echo $tenant_rent['year']; ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php 
                        $invoice_total += $tenant_rent['amount'];
                        echo number_format($tenant_rent['amount']); 
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php
                $invoice_services_total = 0; 
                $invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_services as $invoice_service):
                    if ($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->num_rows() > 0):
            ?>
            <tr>
                <td><?php echo $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->name; ?></td>
                <td><?php echo $invoice_service['month']; ?></td>
                <td><?php echo $invoice_service['year']; ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost); ?>
                    <?php $invoice_services_total += $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost; ?>
                </td>
            </tr>
            <?php
                    endif;
                endforeach; 
            ?>
            <!-- Ends if invoice type is Multiple months or Single Month -->
            <?php endif; ?>
            <?php if ($late_fee > 0) : ?>
            <tr class="item">
                <td><?php echo $this->lang->line('late_fee'); ?></td>
                <td></td>
                <td></td>
                <td><?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($late_fee); ?></td>
            </tr>
            <?php endif; ?>

            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $this->lang->line('total'); ?>: <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($invoice_total + $invoice_services_total + $late_fee); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>