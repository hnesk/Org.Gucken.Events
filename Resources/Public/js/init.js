jQuery(document).ready(function() {

	jQuery('#ajax-indicator').ajaxStart(function() {
		jQuery(this).text('Ajax loading');
	});
	
	jQuery('#ajax-indicator').ajaxStop(function() {
		jQuery(this).text('...');
	});
	

	var initButtons = function() {
		jQuery('.externalIdentifierScheme').change(function() {
			var $selectHolder = jQuery(this).nextAll('span.selectHolder').first();			
			$selectHolder.load($selectHolder.data('url'), {type:$(this).val()});
		});
		jQuery('.button.trash').button({
			text:false,
			icons: {primary: "ui-icon-trash"},
		});
		jQuery('.button.details').button({
			text:false,
			icons: {primary: "ui-icon-info"},
		});
		jQuery('.button.convert').button({
			text:false,
			icons: {primary: "ui-icon-arrowthick-1-w"},
		});


		jQuery('a.details').cluetip({
			attribute:'href',
			arrows: true,    // if true, displays arrow on appropriate side of clueTip
			dropShadow:false,     // set to false if you don't want the drop-shadow effect on the clueTip
			sticky:true,    // keep visible until manually closed
			width:400,
			ajaxCache : true,
			closePosition: 'title',
			closeText: 'X',
			ajaxSettings: {
				cache:true
			}
		});
	}
	jQuery('a.tooltipTrigger').cluetip({
		local:true,
		arrows: true,    // if true, displays arrow on appropriate side of clueTip
		dropShadow:false,     // set to false if you don't want the drop-shadow effect on the clueTip
		width:600,
		height:400,
		sticky:true,    
		closePosition: 'title',
		closeText: 'X'		
	});	
	
	jQuery('.factoids .identity').draggable({
		handle:'span.grip', 
		revert:true,
		distance:20
	});

	var update = function(data) {
		// update dom
		if(data.update) {
			$.each(data.update, function(id, html) {
				console.log('update',id,html);
				$('#'+id).html(html);
			});
		}
		if(data.replace) {
			$.each(data.replace, function(id, html) {
				console.log('replace',id,html);
				$('#'+id).replaceWith(html);
			});
		}
		initButtons();
	};
	
	initButtons();	
	
	jQuery('.identities').droppable({
		accept: '.identity',
		activeClass: "dropaccept",
		hoverClass: "drophover",
		drop: function( event, ui ) {
			var $identity = jQuery(ui.draggable);
			$identity.appendTo(this);
			//jQuery('.ui-draggable').draggable('disable');			
			jQuery.ajax({
				url: $identity.data('mergeurl'),
				data: {event:jQuery(this).parents('.event').data('identity')},
				success:update,
				dataType:'json'
			});
			
			
		}		
	});
		
	
	jQuery('.events').droppable({
		accept: '.identity',
		greedy:true,
		activeClass: "dropaccept",
		hoverClass: "drophover",
		drop: function( event, ui ) {
			jQuery(ui.draggable).appendTo(this);
			jQuery('.ui-draggable').draggable('disable');
			var convertActionLink = $(ui.draggable).find('a.convert').first().attr('href');
			window.location.href = convertActionLink;
		}		
	});
		
	
	jQuery('.autoadd').each(function() {
		var $self = jQuery(this);		
		var $prototype = null;
		var $insertAfter = $self;		
		var data = $self.data('autoadd');
		var $observedElements = $self.find(data.selector);
		var initialNumber = 1 * $observedElements.first().attr('name').replace(/^.+\[(\d+)\].+$/,'$1');
		var currentNumber = initialNumber;
		
		$observedElements.each(function () {
			$self.bind(data.event,function() {
				currentNumber++;
				var $newElement = $prototype.clone(true,true);				
				$newElement.find('input, select, textarea').each(function() {
					var $formElement = jQuery(this);
					
					if ($formElement.attr('name')) {
						$formElement.attr('name', $formElement.attr('name').replace('['+initialNumber+']', '['+currentNumber+']'));
					}
					if ($formElement.attr('id')) {
						$formElement.attr('id', $formElement.attr('id').replace('_'+initialNumber+'_', '_'+currentNumber+'_'));
					}					
				});		
				$insertAfter.after($newElement);							
				$insertAfter = $newElement;

				$focusElement = $newElement.find(data.selector).first();
				$focusElement.focus();
				$focusElement.val('');
			});						
		});
		$prototype = $self.clone(true,true);
	});
});
