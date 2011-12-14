jQuery(document).ready(function() {
    jQuery('.externalIdentifierScheme').change(function() {
        var $selectHolder = jQuery(this).nextAll('span.selectHolder').first();
        $selectHolder.load($selectHolder.data('url'));
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

				
				//window.setTimeout(function(el) {console.log('delay',el);el.focus();}, 1000, $newElement);
			});						
		});
		$prototype = $self.clone(true,true);
	});
});
