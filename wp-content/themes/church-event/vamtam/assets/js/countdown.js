(function($, undefined) {
	'use strict';

	$(function() {
		$('.wpv-countdown').each(function() {
			var days    = $('.wpvc-days .value', this);
			var hours   = $('.wpvc-hours .value', this);
			var minutes = $('.wpvc-minutes .value', this);
			var seconds = $('.wpvc-seconds .value', this);

			var days_word    = $('.wpvc-days .word', this);
			var hours_word   = $('.wpvc-hours .word', this);
			var minutes_word = $('.wpvc-minutes .word', this);
			var seconds_word = $('.wpvc-seconds .word', this);

			var until = parseInt($(this).data('until'), 10);
			var done = $(this).data('done');

			var self = $(this);

			var updateTime = function() {
				var now = Math.round( (+new Date()) / 1000 );

				if(until <= now) {
					clearInterval(interval);
					self.html($('<span />').addClass('wpvc-done wpvc-block').html($('<span />').addClass('value').text(done)));
					return;
				}

				var left = until - now;

				var snum = left % 60;
				seconds.text( snum );
				seconds_word.text( seconds_word.data( snum === 1 ? 'singular' : 'plural' ) );

				left = Math.floor( left / 60 );

				var mnum = left % 60;
				minutes.text( left % 60 );
				minutes_word.text( minutes_word.data( mnum === 1 ? 'singular' : 'plural' ) );

				left = Math.floor( left / 60 );

				var hnum = left % 24;
				hours.text( left % 24 );
				hours_word.text( hours_word.data( hnum === 1 ? 'singular' : 'plural' ) );

				left = Math.floor( left / 24 );
				days.text( left );
				days_word.text( days_word.data( left === 1 ? 'singular' : 'plural' ) );
			};

			var interval = setInterval( updateTime, 1000 );
		});
	});
})(jQuery);