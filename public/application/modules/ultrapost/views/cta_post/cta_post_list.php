<?php $this->load->view('admin/theme/message'); ?>
<?php $only_message_button = $this->uri->segment(3); ?>

<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'><i class="fa fa-arrow-right"></i> <?php echo $this->lang->line("CTA Poster");?> </h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">
			<div class="grid_container" style="width:100%; height:659px;">
				<table 
				id="tt"  
				class="easyui-datagrid" 
				url="<?php echo base_url()."ultrapost/cta_post_list_data"; ?>" 

				pagination="true" 
				rownumbers="true" 
				toolbar="#tb" 
				pageSize="10" 
				pageList="[5,10,15,20,50,100]"  
				fit= "true" 
				fitColumns= "true" 
				nowrap= "true" 
				view= "detailview"
				idField="id"
				>
				
					<!-- url is the link to controller function to load grid data -->					

					<thead>
						<tr>
							<th field="campaign_name" sortable="true"><?php echo $this->lang->line('Campaign Name'); ?></th>
							<th field="page_name" sortable="true"><?php echo $this->lang->line('Publisher'); ?></th>
							<th field="cta_button" sortable="true"><?php echo $this->lang->line('CTA Button'); ?></th>
							<th field="scheduled_at" align="center" sortable="true"><?php echo $this->lang->line('Scheduled at'); ?></th>
							<th field="status" align="center" sortable="true"><?php echo $this->lang->line('Status'); ?></th>
							<th field="actions" align="center"><?php echo $this->lang->line('Actions'); ?></th>
							<th field="error_mesage"><?php echo $this->lang->line('Error'); ?></th>
							
						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

			<div class="row">
				<div class="col-xs-12">
					<a class="btn btn-primary" href="<?php echo base_url("ultrapost/cta_poster/{$only_message_button}");?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('New Post'); ?></a>
				</div>
			</div>
 

			<form class="form-inline" style="margin-top:20px">

				<div class="form-group">
					<input id="campaign_name" name="campaign_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Campaign Name'); ?>">
				</div>

				<div class="form-group">
					<input id="page_name" name="page_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Page Name'); ?>">
				</div>   

				<div class="form-group">
					<input id="scheduled_from" name="scheduled_from" class="form-control datepicker" size="20" placeholder="<?php echo $this->lang->line('Scheduled from'); ?>">
				</div>

				<div class="form-group">
					<input id="scheduled_to" name="scheduled_to" class="form-control  datepicker" size="20" placeholder="<?php echo $this->lang->line('Scheduled to'); ?>">
				</div>                    

				<button class='btn btn-info'  onclick="doSearch(event)"><i class="fa fa-search"></i> <?php echo $this->lang->line("search");?></button> 
							
			</div>  

			</form> 

			</div>        
		</div>
	</div>   
</section>


<script>

	$j('.datepicker').datetimepicker({
    theme:'light',
    format:'Y-m-d',
    formatDate:'Y-m-d',
    timepicker:false
    });

	$(document.body).on('click','.delete',function(){ 
		var id = $(this).attr('id');
		 alertify.confirm('<?php echo $this->lang->line("are you sure");?>',"<?php echo $this->lang->line('Do you really want to delete this post from our database?'); ?>", 
		  function(){ 
		    $.ajax({
		       type:'POST' ,
		       url: "<?php echo base_url('ultrapost/delete_post')?>",
		       data: {id:id},
		       success:function(response)
		       {
		       	if(response=='1')
		        {
		        	$j('#tt').datagrid('reload');
		        	alertify.success('<?php echo $this->lang->line("your data has been successfully deleted from the database."); ?>');
		        }
		       	else 
		       	alertify.alert('<?php echo $this->lang->line("Alert");?>','<?php echo $this->lang->line("something went wrong, please try again.");?>',function(){});
		       }
			});
		  },
		  function(){     
		  });
	});
	

	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{             
			campaign_name   :     $j('#campaign_name').val(),              
			page_name  :     $j('#page_name').val(),              
			scheduled_from  		:     $j('#scheduled_from').val(),    
			scheduled_to    		:     $j('#scheduled_to').val(),         
			is_searched		:      1
		});

	} 
	
	
</script>

