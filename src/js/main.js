(function($){
	// Modified http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
	// Only fires on body class (working off strictly WordPress body_class)
	var IP = {
		// All pages
		common: {
		    init: function() {
		     	// JS here
		    },
		    finalize: function() { }
		},
		// Home page
		home: {
		    init: function() {
		      	// JS here
				// $grid = $('#content-grid').packery({
				// 		// options
				// 		itemSelector: '.grid-item',
				// 		gutter: 10
				// 	});

				// $grid.find('.grid-item').each( function( i, gridItem ) {
				// var draggie = new Draggabilly( gridItem );
				// // bind drag events to Packery
				// $grid.packery( 'bindDraggabillyEvents', draggie );
				// });

				// $grid.on( 'click', '.grid-item .close', function( event ) {
				// // remove clicked element
				// $grid.packery( 'remove',  $(event.currentTarget).parent() )
				// 	// shiftLayout remaining item elements
				// 	.packery('shiftLayout');
				// });

				//open projects
				$('.open-project').click(function(e){
					e.preventDefault();

					$.get( $(e.target).attr('data-anchor') , function( data ) {
						var id = $(e.target).attr('href');
						modal = UIkit.modal(id);

						$(id + '> .uk-modal-dialog').html($(data).find('#content'));

						
						modal.show();
					});	
				});
				// $('.uk-modal').on({
				// 	'show.uk.modal': function(e){
				// 		console.log("Modal is waiting.");
				// 		console.log(e);
				// 		// var modal = $(this);

				// 		// modal.hide();
				// 		setTimeout({function(){
				// 			console.log(modal);
				// 		}}, 3000);
				// 	}});
		    }
		},
	  	// About page
	  	about: {
		    init: function() {
		      	// JS here
		    }
	  	}
	};

	var UTIL = {
	  	fire: function(func, funcname, args) {
	    	var namespace = IP;
	    	funcname = (funcname === undefined) ? 'init' : funcname;
	    	if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
		      	namespace[func][funcname](args);
		    }
	  	},
	  	loadEvents: function($) {

		    UTIL.fire('common');

		    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
		      UTIL.fire(classnm);
		    });

	    	UTIL.fire('common', 'finalize');
	  	}
	};


	//you can add new functions here





	jQuery(document).ready(UTIL.loadEvents);

})(jQuery);



// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
	window.getComputedStyle = function(el, pseudo) {
		this.el = el;
		this.getPropertyValue = function(prop) {
			var re = /(\-([a-z]){1})/g;
			if (prop === 'float') { prop = 'styleFloat'; }
			if (re.test(prop)) {
				prop = prop.replace(re, function () {
					return arguments[2].toUpperCase();
				});
			}
			return el.currentStyle[prop] ? el.currentStyle[prop] : null;
		};
		return this;
	};
}


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
	var doc = w.document;
	if( !doc.querySelector ){ return; }
	var meta = doc.querySelector( "meta[name=viewport]" ),
		initialContent = meta && meta.getAttribute( "content" ),
		disabledZoom = initialContent + ",maximum-scale=1",
		enabledZoom = initialContent + ",maximum-scale=10",
		enabled = true,
		x, y, z, aig;
	if( !meta ){ return; }
	function restoreZoom(){
		meta.setAttribute( "content", enabledZoom );
		enabled = true; }
	function disableZoom(){
		meta.setAttribute( "content", disabledZoom );
		enabled = false; }
	function checkTilt( e ){
		aig = e.accelerationIncludingGravity;
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
		if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
			if( enabled ){ disableZoom(); } }
		else if( !enabled ){ restoreZoom(); } }
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );