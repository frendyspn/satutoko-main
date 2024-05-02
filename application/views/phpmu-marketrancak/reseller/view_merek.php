<script> 
$(document).ready(function(){
    $(".flip").click(function(){
        $(".panel").toggle();
    });
});
</script>

<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url(); ?>produk">Produk</a></li>
                <li><?php echo $judul; ?></li>
            </ul>
        </div>
    </div>
    <div class="ps-page--shop" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout--shop">
                <?php include "sidebar-produk.php"; ?>
                <div class="ps-layout__right">
                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-shopping__header">
                            <p>Terdapat <strong><?php echo $records->num_rows(); ?></strong> Merek</p>
                        </div>

                            <div class="ps-shopping-product">
                                <ul>
                                <?php 
                                    foreach ($record->result_array() as $row){
                                        $total = $this->db->query("SELECT * FROM rb_produk where tag LIKE '%$row[tag_seo]%'")->num_rows();
                                        echo "<li><a style='border-bottom:1px dotted #cecece; display:block' href='".base_url()."tag/produk/$row[tag_seo]'>$row[nama_tag] <span class='pull-right'>($total)</span></a></li>";
                                    }
                                ?>
                                </ul>

                                <div class="ps-pagination">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>
                            </div>

                            
                    </div>
                </div>
            </div>
        </div>
    </div>