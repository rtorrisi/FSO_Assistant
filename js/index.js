$('.tab a').on('click', function (e) {

  e.preventDefault();

  $(this).parent().siblings().removeClass('active');
  $(this).parent().addClass('active');
  
  $('.tab2').siblings().removeClass('active');
  $( $(this).attr('href')+"t1" ).addClass('active');

  target = $(this).attr('href');
  $('.tab-content > div').not(target).hide();
  $(target).fadeIn(600);

  subTarget = $(this).attr('href')+"1";
  $('.tab2-content > div').not(subTarget).hide();
  $(subTarget).fadeIn(600);

});

$('.tab2 a').on('click', function (e) {

  e.preventDefault();
  $(this).parent().siblings().removeClass('active');
  $(this).parent().addClass('active');

  target = $(this).attr('href');
  $('.tab2-content > div').not(target).hide();

  $(target).fadeIn(600);

});

$('.form').find('input, textarea').on('keyup blur focus', function (e) {

  var $this = $(this),
      label = $this.prev('label');

	  if (e.type === 'keyup') {
			if ($this.val() === '') {
          label.removeClass('active highlight');
        } else {
          label.addClass('active highlight');
        }
    } else if (e.type === 'blur') {
    	if( $this.val() === '' ) {
    		label.removeClass('active highlight');
			} else {
		    label.removeClass('highlight');
			}
    } else if (e.type === 'focus') {

      if( $this.val() === '' ) {
    		label.removeClass('highlight');
			}
      else if( $this.val() !== '' ) {
		    label.addClass('highlight');
			}
    }

});
