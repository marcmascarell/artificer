<?php namespace Mascame\Artificer\Plugins\Plupload;

use Mascame\Artificer\Widgets\Widget;

class PluploadWidget extends Widget {

	public function output()
	{
		?>
		<script src="<?=$this->package_assets?>/widgets/plupload/plupload.full.min.js"></script>

		<style>
			.plupload-preview .thumbnail {
				display: inline-block;
				width: 150px;
			}

			.plupload-preview {
				margin-top: 10px;
			}
		</style>

		<script>
			$(function() {
				var $file_list = $('#plupload-file-list');
				var uploader = new plupload.Uploader({
					browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
					url: $('#admin-form').data('file-upload'),

					runtimes : 'html5',
					max_file_size : '10mb',
					chunk_size : '1mb',
					unique_names : true,
					dragdrop : true,
					multiple_queues : false,
					multi_selection : false,
					max_file_count : 1,

					init : {
						FilesAdded: function(up, files) {
							up.start();
						}
					}

				});

				uploader.init();

				uploader.bind('FilesAdded', function(up, files) {
					var html = '';
					plupload.each(files, function(file) {
						html += '<li id="' + file.id + '" class="list-group-item">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
					});
					$file_list.append(html);
				});

				uploader.bind('FileUploaded', function(up, files, info) {
					console.log(info.response);

					response = JSON.parse(info.response);

					$('input[name="image"]').closest('.form-group').css('background', '#'+Math.floor(Math.random()*16777215).toString(16));
					$('.plupload-preview').html('<div class="thumbnail"><img height="150" width="150" class="img-responsive" src="/uploads/'+ response.filename +'"></div>');

//				if((plupRes.state) == "success") {
//					$("#listing").append("<li><img src=\"files/photos/" + plupRes.user + "/thumbs/" + plupRes.file + "\" alt=\"\" /></li>\n");
//				}
//				console.log(up);
//				console.log(files);
				});

				uploader.bind('UploadComplete', function(up, file) {
					$file_list.empty();
				});

				uploader.bind('UploadProgress', function(up, file) {
					document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
				});

				uploader.bind('Error', function(up, err) {
					document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
				});

				$('#start-upload').click(function() {
					uploader.start();
				});

			});
		</script>
		<?php
	}

}