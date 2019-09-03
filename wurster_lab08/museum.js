'use strict'

//run script after loaded
$(()=> {
	//reset button listener
	$('.reset').on('click', function() {
		$('input').prop('checked', false);
	});

	//multi-select listener
	$('.top').on('click', function(e) {
		let picked = $(this).attr('class').split(' ')[1];
		$('.' + picked).prop('checked', $(this).prop('checked'));
	});

	//confirms delete button and sets values just prior to submission
	$('.d').on('click', function(e) {
		let edit = $(this);
		if(confirm('deleting this entry: \n\n' + edit.parent().parent()[0].textContent.replace(/\s+/g," "))) {
			edit.removeClass('d ');
			let id = edit.attr('class').slice(2, edit.attr('class').length);
			edit.val(id);
			edit.hide();
		}else{
			e.preventDefault();
		}
	});

	//prompts for new museum name and sets values just prior to submission
	$('.e').on('click', function(e) {
		let edit = $(this);
		let x = prompt('What is the new name?')
		if(x) {
			edit.removeClass('e ');
			let id = edit.attr('class').slice(2, edit.attr('class').length);
			edit.val(id + "++" + x);
			edit.hide();
		}else{
			e.preventDefault();
		}
	});


//end of pageload func 
});
