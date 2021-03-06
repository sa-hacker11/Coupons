<?=$this->load->view(branded_view('cp/header'));?>
<script>
jQuery(document).ready(function(){
    $('.pagination .number a, .pagination .last a, .pagination .first a, .pagination .next a, .pagination .previous a').on('click', function(event){
        event.preventDefault();
        var filterz = $('input[name="filterz"]').val();
        var link_array = this.href;
        var filter_var = link_array.split('=');
        var merged_category = $('input[name="merged_category"]').val();
        var all_page_category = $('input[name="check_category[]"]').map(function() {return this.value;}).get().join(',');
        var check_category = $('input[name="check_category[]"]:checked').map(function() {return this.value;}).get().join(',');        
        var name_var = $('input[name="nume"]').val();
        var mid_var = $('input[name="mid"]').val();
        var offset = parseInt(filter_var[5]);
        if(true === isNaN(offset)) {
            offset = 0;
        }
        //alert(filters);
        var limit = parseInt(filter_var[4]);       

        $.ajax({
            type: 'post',
            url: '/admincp2/linkshare/updateFilters/',
            data: 'filterz='+filterz+'&all_page_category='+all_page_category+'&check_category='+check_category+'&merged_category='+merged_category+'&nume='+name_var+'&mid='+mid_var+'&offset='+offset+'&limit='+limit,
            dataType:'html',
            success: function(data, textStatus, XMLHttpRequest) {
                $('input[name="filterz"]').val(data);
                //console.log('after='+$('input[name="filterz"]').val());
                document.forms['dataset_form'].method='post';
                document.forms['dataset_form'].submit();
            }
        });
    });
    $('#save').on('click', function(event){
        if($('#merged_category').val() == ''){
            alert('Field Merged Category is empty!');
            $('#merged_category').css("border-color", "#ff0000");
            return false;
        }
  
        $('input[name="saving"]').val('ok');
        document.forms['dataset_form'].method='post';
        document.forms['dataset_form'].submit();
    });
});
</script>
<h1>Join Creative Categories</h1>
<div><strong>INSTRUCTION</strong>: First search a keyword on Name filter, chose category ( checkboxes ) you want to merge then write a name for the NEW category and press SAVE !<br/><br/></div>

<?=$this->dataset->table_head();?>
    <input type="hidden" name="filterz" value="<?php echo $filterz; ?>"/>
    <input type="hidden" name="saving" value=""/>
    	<?
	if (!empty($this->dataset->data)) {
		foreach ($this->dataset->data as $row) {
		?>
			<tr>			
				<td align="left"><?=$row['id'];?></td>
                                <td align="left"><input type="checkbox" name="check_category[]" value="<?=$row['cat_id']?>" class="action_items" <?php if(($row['checked'])==1){echo 'checked';}?>></td>
                                <td align="left"><?=$row['id_site'];?></td>
                                <td align="left"><?=$row['cat_id'];?> 
                                    <?php if(!empty($row['merge_categories'])){ ?>
                                    <span style="color:#FF0000;">| included in: </span>
                                    <strong><?php foreach($row['merge_categories'] as $name){ echo $name." &nbsp;<span style='color:#ff0000'>/</span> &nbsp;"; }?></strong>
                                    <?php } else { echo "<span style='color:#ff0000'>|</span> Not merged!"; } ?>
                                </td>
				<td align="center"><?=$row['name'];?></td>
                                <td align="center"><?=$row['mid'];?></td>
                                <td align="left"><?=$row['nid'];?></td>
                                <td  align="left" class="options">
                                    <script>
                                        $('input[name="nume"]').val('<?=$name_search;?>');
                                        $('input[name="mid"]').val('<?=$mid_search;?>');
                                    </script>
                                    
                                    <a href="<?=site_url('admincp2/linkshare/editCreativeCategory/' . $row['id']);?>">editeaza</a>
				</td>
			</tr>
		<?
		}
	}
	else {
	?>
	<tr>
		<td colspan="7">Nu sunt categorii creative.</td>
	</tr>
	<? } ?>
        <tr>
        <tr>
            <td colspan="8" style="background-color: #B9E2FA;"><div><span style="font-size: 16px; font-weight:bold; ">Chose a name for the new Merged Category: </span><input type="text" id="merged_category" name="merged_category" value="<?php if(isset($name_merged)){echo $name_merged;}?>"><button id="save" type="button">Save</button></div></td>
        </tr>

<?=$this->dataset->table_close();?>

<?=$this->load->view(branded_view('cp/footer'));?>