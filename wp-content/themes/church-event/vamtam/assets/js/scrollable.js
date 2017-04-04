(function($, undefined) {
	"use strict";

	$.rawContentHandler(function(context) {

		var whitespace = 0;
		var win_width;
		var defaults = {
			pager: false,
			controls: true,
			minSlides: 1,
			maxSlides: 10,
			infiniteLoop: false,
			hideControlOnEnd: true
		};

		var container = $('.portfolios.scroll-x, .loop-wrapper.scroll-x, .woocommerce-scrollable.scroll-x', context || document);

		container.find("img.lazy").not(".jail-started, .loaded").addClass("jail-started").jail({
			speed : 1000,
			event : false
		});

		var scrollable_reduce_column_count = function( columns ) {
			if ( ! $( 'body' ).hasClass( 'responsive-layout' ) ) {
				return columns;
			}

			win_width = $( window ).width();

			if ( win_width < 770 ) {
				return 1.1;
			}

			if ( win_width >= 768 && win_width <= 1024 ) {
				return Math.min(columns, 2);
			}

			if ( win_width > 1024 && win_width < 1280 ) {
				return Math.min(columns, 3);
			}

			return columns;
		};

		var calcSlideWidth = function( maxSlides ) {
			var columns = scrollable_reduce_column_count( maxSlides );

			var wrapper          = this.closest('.scrollable-wrapper');
			var local_whitespace = whitespace;
			var wrapper_width    = wrapper.width();

			if ( wrapper.has( '.woocommerce-scrollable' ).length ) {
				local_whitespace = wrapper_width * 0.03875;
			} else {
				local_whitespace = win_width > 480 ? 30 : 5;
			}

			return {
				width: ( wrapper_width - local_whitespace * ( columns - 1 ) ) / columns,
				margin: local_whitespace
			};
		};

		var reloadSlider = function(el, maxSlides, slideWidth) {
			if( ! el || ! el.data('bxslider') || ! el.data('scrollable-loaded') )
				return;

			el.data('scrollable-loaded', false);

			var newSlideWidth = calcSlideWidth.call(el, maxSlides);

			if ( newSlideWidth.width !== slideWidth.width && el.data('bxslider') ) {
				slideWidth = newSlideWidth;

				el.data('bxslider').reloadSlider($.extend(defaults, {
					slideWidth: newSlideWidth.width,
					slideMargin: newSlideWidth.margin
				}));
			}

			return slideWidth;
		};

		container.each(function() {
			var el = $('> ul', this),
				maxSlides = parseInt(el.data('columns'), 10),
				slideWidth = calcSlideWidth.call(el, maxSlides);

			el.data('bxslider', el.bxSlider($.extend(defaults, {
				slideWidth: slideWidth.width,
				slideMargin: slideWidth.margin,
				onSliderLoad: function() {
					el.data('scrollable-loaded', true);

					if(el.data('wpv-loaded-once')) return;

					el.data('wpv-loaded-once', true);

					el.imagesLoaded(function() {
						if ( el.data('bxslider') && 'redrawSlider' in el.data('bxslider') ) {
							el.data('bxslider').redrawSlider();

							setTimeout(function() {
								el.data('bxslider').redrawSlider();
							}, 1500);
						}
					});

					setTimeout(function() {
						$(window).smartresize(function() {
							slideWidth = reloadSlider(el, maxSlides, slideWidth);
						});

						el.data('bxslider').redrawSlider();
					}, 1500);

					el.bind('vamtam-video-resized', function() {
						if ( 'redrawSlider' in el.data('bxslider') ) {
							el.data('bxslider').redrawSlider();
						}
					});
				}
			})));
		});
	});
})(jQuery);