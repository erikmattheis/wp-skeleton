(function($) {

	$(document).ready(function() {

		$('.gsn-settings').each(function() {
			var $container = $(this);

			$('.reveal-form a', $container).click(function() {
				var $form = $('form', $container);
				if ('block' == $form.css('display')) {
					$form.hide();
				}
				else {
					$form.show();
				}
				return false;
			});
		});

		$('.button.remove-keys').click(function() {
			$('input[name=client_id],input[name=client_secret]').val('');
		});

	});

})(jQuery);