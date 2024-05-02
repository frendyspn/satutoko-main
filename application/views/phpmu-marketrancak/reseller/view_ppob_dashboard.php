
<aside class="ps-widget--account-dashboard" style='margin-top:10px'>
    <div class="ps-widget__content">
        <ul>
            <li><a class='<?= ($_GET['ppob'] == '' ? 'active' : ''); ?>' href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>">Dashboard</a></li>
            <?php 
                $idn = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
            ?>
            <li><a target='_BLANK' href="<?php echo "https://api.whatsapp.com/send?phone=".preg_replace('/\s+/', '', format_telpon($idn['no_telp']))."&amp;text=Hallo,%20Saya%20Butuh%20Bantuan%20Terkait%20PPOB..."; ?>">Layanan Bantuan</a></li>
        </ul>
    </div>
</aside>