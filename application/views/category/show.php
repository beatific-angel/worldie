<?php //echo"<pre>";print_r($servicedata);die; ?>
<div class="row" style="margin-top:10px;">
		<div class="col-lg-3 in-gp-tl">
			<div class="input-group-btn">
				<a href="javascript:;" class="btn btn-primary" id="add_favorite" service-id="<?php echo $servicedata[0]->pID;?>"><i class="fa fa-star-o" aria-hidden="true"></i>
Favorite</a>
			</div>
		</div>
		<div class="col-lg-2 in-gp-tl">
			<div class="input-group-btn">
			    <a href="#" data-toggle="modal" data-target="#myModal4" class="btn btn-primary"><i class="fa fa-share-alt" aria-hidden="true"></i>    Share</a>
			</div>
		</div>
</div> 
<h3 class="agileinfo_sign"><?php echo $servicedata[0]->pName;?></h3>
<div class="row">
<div class="pop-in-d">
	<ul>
	<li>
		<label><i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
         </label><span><?php echo $servicedata[0]->addressLine1;?></span>
	</li>
	<li>
		<label><i class="fa fa-phone fa-2x" aria-hidden="true"></i>
</label><span><?php echo $servicedata[0]->phoneNumber;?></span>
	</li>
	<li>
		<label><i class="fa fa-globe fa-2x" aria-hidden="true"></i>
</label><span><?php echo $servicedata[0]->website;?></span>
	</li>
	<hr>
	<li>
		<label>Description:</label><span class="nxt-lin"><?php echo $servicedata[0]->pDescription;?></span>
	</li>
	<li>
		<label>Primary Services:</label>
        <span class="nxt-lin2">
        <ul>
		<?php foreach ($servicedata['services'] as $service): ?>
			<li><?php echo $service->serviceCodeDescription;?></li>
		<?php endforeach; ?>
        </ul>
        
        </span>
	</li>
	<hr>
	<li>
		<label>Hours:</label><span class="nxt-lin"><?php echo $servicedata[0]->pHours;?></span>
	</li>
	</ul>
    </div>
</div>

<script>
/* -------------Add to favorite start---------*/
		$('#add_favorite').click(function(e) {
			var serviceid = $(this).attr('service-id');
			$.ajax({
					type: "GEt",
					url: "<?php echo base_url().'welcome/addFavorite';?>"+'/'+serviceid,
					success: function(response){
						if(response == 'success'){
							$('#add_favorite').html('<i class="fa fa-star" aria-hidden="true" style="color:#ffb732;"></i>'+' '+'Favorite');
						}
						//alert(response);
					}
				});
        });
	/* -------------Add to favorite end---------*/
</script>
	
