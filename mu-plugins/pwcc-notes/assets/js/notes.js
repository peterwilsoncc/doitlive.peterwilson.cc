/* global jQuery, twttr */

( function ( window ) {
	const $ = window.jQuery;
	const twttr = window.twttr;
	const $metaBox = $( '#pwccindieweb_notes_metabox' );
	const maxTweetLength = 280;
	const maxPermillage = 1000;

	// eslint-disable-next-line arrow-parens
	const initSection = ( $section ) => {
		// Accepts either a DOMElement or a jQuery object.
		$section = $( $section );

		// Get various elements within the note form.
		const $text = $section.find( '.pwccindieweb-note-text' );
		const $textLabel = $section.find( 'label[for="' + $text.attr( 'id' ) + '"]' );
		const $appendUrl = $section.find( '.pwccindieweb-note-append-url' );
		let $textCount = $textLabel.find( '.pwccindieweb-note-text-counter' ).first();

		if ( $textCount.length === 0 ) {
			$textLabel.append( `<span class="pwccindieweb-note-text-counter">${maxTweetLength}</span>` );
			$textCount = $textLabel.find( '.pwccindieweb-note-text-counter' ).first();
		}

		const calculateLength = () => {
			let tweetVal = $text.val();
			if ( $appendUrl.filter( ':checked' ).val() === '1' ) {
				tweetVal += ' http://example.com/';
			}
			const status = twttr.txt.parseTweet( tweetVal );
			const remaining = maxTweetLength - status.weightedLength;
			const permillage = status.permillage/maxPermillage;
			$textCount.text( remaining );

			if ( permillage > 0.93 ) {
				$textCount.css( 'color', '#d40d12' );
			} else if ( permillage > 0.85 ) {
				$textCount.css( 'color', '#5c0002' );
			} else {
				$textCount.css( 'color', '' );
			}
		};

		calculateLength();
		$text.on( 'input', calculateLength );
		$appendUrl.on( 'change', calculateLength );
	};

	const initSections = () => {
		const $sections = $metaBox.find( '[data-class="CMB_Group_Field"]' );

		for ( let i=0, l = $sections.length; i<l; i++ ) {
			let $section = $( $sections[i] );
			if ( $section.data( 'pwccNotesMB' ) === true ) {
				continue;
			}
			$section.data( 'pwccNotesMB', true );
			initSection( $section );
		}
	};

	initSections();
} )( window );
