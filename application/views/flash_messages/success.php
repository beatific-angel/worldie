<div id="success_msg" class="alert alert-success alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
    <strong><?php echo $msg; ?></strong> 
</div>
<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function(){$('#success_msg').fadeOut();}, 2000);
	});
</script>