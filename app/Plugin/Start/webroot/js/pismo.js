var PISMO = Class.extend({
	init: function () {
		
		var self = this;
		
		// SEND
		
		this.lettersSendDiv = $('.lettersSend');
		this.lettersSendButton = this.lettersSendDiv.find('button[data-action="send"]');
		this.lettersSendModal = $('#sendPismoModal');

		this.lettersSendButton.click( $.proxy(this.showSendModal, this) );
		
		
		// RESPONSES
		
		this.responsesDiv = $('.lettersResponses');
		this.responsesList = this.responsesDiv.find('.responses');
		this.responsesButtons = this.responsesDiv.find('.buttons');
		this.addResponseButton = this.responsesDiv.find('button[data-action=add_response]').first();
		this.letterAlphaId = this.addResponseButton.data('letter-alphaid');
		this.letterSlug = this.addResponseButton.data('letter-slug');

		// SET NAME
		var self = this;
		$('input.h1-editable').each(function() {
			$(this).change(function() {
				$.post($(this).data('url') + '.json', {
					nazwa: $(this).val(),
					edit_from_inputs: 1
				}, function(res) {
					// @todo error handler
					console.log(res);
				});
				$(this).blur();
			});
		});

		this.addResponseButton.click( $.proxy(this.addResponseForm, this) );
		
	},
	showSendModal: function(event) {
		
		event.preventDefault();
		
		// this.lettersSendModal.find('#senderName').val($.trim(self.html.stepper_div.find('.control.control-sender').text()).split('\n')[0]);
		this.lettersSendModal.modal('show');
		
		/*
		this.lettersSendButton.click(function (e) {
			e.preventDefault();

			$sendPismoModal.find('#senderName').val($.trim(self.html.stepper_div.find('.control.control-sender').text()).split('\n')[0]);
			$sendPismoModal.modal('show');
		});

		if (modal.sendPismo.length) {
			modal.sendPismo.find('.btn[type="submit"]').click(function () {
				var correct = true;
				$.each(modal.sendPismo.find('input:required'), function () {
					if ($(this).val() == "") {
						$(this).val('');
						correct = false;
						return false;
					} else {
						if ($(this).attr('type') == "email") {
							var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

							if (!emailReg.test($(this).val())) {
								$(this).focus();
								correct = false;
								return false;
							}
						}
					}
				});
				if ($(this).hasClass('loading')) {
					correct = false;
					return false;
				}
				if (correct) {
					$(this).addClass('loading');
				}
			});
		}
		*/
		
		
		
		
		
		
		
	},
	addResponseForm: function() {

		var li = $('<li class="response response-form"><div class="well bs-component mp-form"><form class="letterResponseForm margin-top-10" method="post"  data-url="/moje-pisma/' + this.letterAlphaId + ',' + this.letterSlug + '/responses.json" action="/moje-pisma/' + this.letterAlphaId + ',' + this.letterSlug + '/responses.json"><fieldset>      <legend>Dodaj odpowiedź na to pismo:</legend><div class="row margin-top-10">                             <div class="col-md-9">                                 <div class="form-group">                                     <label for="responseName">Tytuł:</label>                                     <input maxlength="195" type="text" class="form-control" id="responseName" name="name">                                 </div>                             </div>                             <div class="col-md-3">                                 <div class="form-group">                                     <label for="responseDate">Data:</label>                                     <input type="text" value="" class="form-control datepickerResponseDate" id="responseDate"  name="date">                                 </div>                             </div>                         </div>                         <div class="form-group">                             <label for="responseContent">Treść:</label>                             <textarea class="form-control" rows="7" id="responseContent" name="content"></textarea>                         </div>                         <div class="form-group">                             <label for="collectionDescription">Załączniki:</label>                             <div class="dropzoneForm">                                 <div class="actions">                                     <a class="btn btn-sm btn-default btn-addfile">Dodaj załącznik</a>                                 </div>                                 <div class="dropzoneFormPreview" id="preview"></div>                             </div>                         </div>                         <div class="form-group overflow-hidden text-center margin-top-20"><button data-action="cancel" class="btn btn-default" type="button">Anuluj</button><button class="btn auto-width btn-primary btn-icon" type="submit">                                 <i class="icon glyphicon glyphicon-ok"></i>                                 Zapisz odpowiedź</button>                         </div></fieldset></form></div></li>');
		this.responsesList.append(li.hide());	
		
		li.slideDown(function(){
					
		});
		
		$('html').animate({scrollTop: li.offset()['top']});
		
		this.responsesButtons.slideUp();
		li.find('button[data-action=cancel]').click($.proxy(function(event){
			
			event.preventDefault();
			li.slideUp(function(){
				
				li.remove();
				
			});
			this.responsesButtons.slideDown();

		}, this));

		$('form.letterResponseForm').each(function() {

			var form = $(this),
				url = form.data('url'),
				dropzone = form.find('.dropzoneForm').first(),
				DropZone = {},
				datepicker = form.find('.datepickerResponseDate').first(),
				btn = form.find('.btn-addfile').first();

			datepicker.bootstrapDP({
				language: 'pl',
				orientation: 'auto top',
				format: "yyyy-mm-dd",
				autoclose: true
			});

			DropZone = new Dropzone(dropzone[0], {
				url: url,
				init: function() {
					var self = this;
					self.on('success', function(file, response) {
						if(response === true) {
							$(file.previewElement)
								.find('.progress-bar')
								.first()
								.addClass('progress-bar-success');
						}
					});

					self.on('error', function(file, response) {
						$(file.previewElement)
							.find('.progress-bar')
							.first()
							.addClass('progress-bar-danger');
					});
				},
				clickable: '.btn-addfile',
				createImageThumbnails: false,
				acceptedFiles: '.pdf,.docx,.doc,.tif,.html,.jpg,.xml,.xls,.xlsx,.rtf,.png',
				autoQueue: true,
				autoProcessQueue: true,
				previewsContainer: '#preview',
				previewTemplate: [
					'<div class="file">',
					'<div class="title">',
					'<span class="name" data-dz-name></span>',
					'<span class="size" data-dz-size></span>',
					'<span class="error text-danger" data-dz-errormessage></span>',
					'</div>',
					'<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">',
					'<div class="progress-bar" style="width:0%;" data-dz-uploadprogress>',
					'</div>',
					'</div>',
					'<div class="buttons">',
					'</div>',
					'</div>'
				].join('')
			});

		});
		
	}
});

var $P;
$(document).ready(function () {
	
	"use strict";
	$.fn.bootstrapDP = $.fn.datepicker.noConflict();
	$P = new PISMO();

	/*
	if ($('#clipboardCopyBtn').length) {
		var client = new ZeroClipboard(document.getElementById("clipboardCopyBtn"));

		client.on("ready", function (readyEvent) {
			if (readyEvent) {
				client.on("aftercopy", function (event) {
					alert("Skopiowano do schowka: " + event.data["text/plain"]);
				});
			}
		});
	}
	*/
	
	var fv = $('#form-visibility');	
	var fvd = fv.find('.form-visibility-display');
	if( fv.length ) {
		
		fv.find('input[name=is_public]').change(function(e){
			
			var input = $(e.currentTarget);
			if( input.val() == '1' ) {
				
				fvd.slideDown();
				
			} else {
				
				fvd.slideUp();
				
			}
			
		});
		
		var radio_inputs_div = $('#visibility_inputs');
		var radio_value = radio_inputs_div.data('value');
		radio_inputs_div.find('input').filter('[value=' + radio_value + ']').prop('checked', true);
		
	}
	
});
