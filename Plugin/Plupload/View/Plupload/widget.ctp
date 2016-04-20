<?php $this->Plupload->loadAsset($ui);?>
<?php echo $this->Form->create(null, array('type' => 'post', 'action' => 'widget'));?>
	<div id="uploader">
		<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
	</div>
<?php echo $this->Form->end();?>

<!--<div id="results" style="overflow: auto; height: 360px; border: solid 5px;"></div>-->

<script type="text/javascript">
$(function() {
	$("#uploader").<?php echo ($ui == 'jquery') ? 'pluploadQueue' : 'plupload';?>({
		<?php echo $this->Plupload->getOptions();?>

		preinit: attachError,
		<?php echo $additionalCallbacks; ?>
	});
	//*
	// Attach widget callbacks
	function attachError(Uploader) {
	    Uploader.bind('FileUploaded', function(up, file, response) {
	        var data = $.parseJSON(response.response);
	        console.log('[FileUploaded] Response - ' + response.response);

	        if (data.code > 0) {
	            up.trigger("Error", {message: "'" + data.message + "'", file: file});
	            console.log('[Error] ' + file.id + ' : ' + data.message);
	            return false;
	        }
	    });
	}
	//*/
	/*
	var $uploader = $('#uploader').plupload('getUploader');
	$uploader.bind('FileUploaded', function(up, file, response){
		$('#results').append('<p>' +file.name+"::"+response+"<br>"+ response.response + '</p>');
	});
	//*/

	$('form').submit(function($e) {

		var $uploader = $('#uploader').plupload('getUploader');

		if ($uploader.total.uploaded == 0) {
			if ($uploader.files.length > 0) {
				$uploader.bind('UploadProgress', function() {
					if ($uploader.total.uploaded == $uploader.files.length)
						$('form').submit();
				});
				$uploader.start();
			} else{
				alert('You must at least upload one file.');
			}
			$e.preventDefault();
		}
	});

});
</script>