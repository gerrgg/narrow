var slideout = new Slideout({
    'panel': document.getElementById('page'),
    'menu': document.getElementById('menu'),
    'padding': 256,
    'tolerance': 70
  });

  // Toggle button
  document.querySelector('.mobile-menu-button > a').addEventListener('click', function() {
	slideout.toggle();
	console.log( 'click' );
  });

  function close(eve) {
	eve.preventDefault();
	slideout.close();
  }
  
  slideout
	.on('beforeopen', function() {
	  this.panel.classList.add('panel-open');
	})
	.on('open', function() {
	  this.panel.addEventListener('click', close);
	})
	.on('beforeclose', function() {
	  this.panel.classList.remove('panel-open');
	  this.panel.removeEventListener('click', close);
	});

	// BACK BURNER**
	// jQuery(document).on( 'found_variation', 'form.cart', function( event, variation ) {
	// 	/* Your code */           
	// 	console.log( event, variation );
	// });