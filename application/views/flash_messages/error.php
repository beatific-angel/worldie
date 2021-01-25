<div id="error_msg" class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
    <strong><?php echo $msg; ?></strong> 
</div>

<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function(){$('#error_msg').fadeOut();}, 2000);
	});
</script>