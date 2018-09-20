$(document).ready(printYear);

function printYear(){
  $("#year").text(new Date().getFullYear());
}

var links = document.getElementsByClassName('navbar-nav')[0].getElementsByTagName("a");
for (var i = 0; i < links.length; i++) {
	if (links[i].getAttribute("href") === window.location.pathname) {
		links[i].parentElement.className = "active";
		break;
	}
}

var filename = window.location.pathname;
if (filename === '/EventWeb/event.php') {
	function setEventModal(eventName, eventDetail, isEventEnded) {
		var modalContent = document.getElementsByClassName('modal-content')[0];

		// Aesthetics 
		if (eventDetail !== 'ERROR: No such event.') {
			modalContent.getElementsByClassName('modal-title')[0].textContent = eventName;
			modalContent.getElementsByClassName('event-organizer')[0].textContent = eventDetail['organizer'];
			modalContent.getElementsByClassName('event-time')[0].textContent = eventDetail['time'];
			modalContent.getElementsByClassName('event-date')[0].textContent = eventDetail['date'];
			modalContent.getElementsByClassName('event-venue')[0].textContent = eventDetail['venue'];
			modalContent.getElementsByClassName('event-description')[0].textContent = eventDetail['description'];
		}

		// Inputs
		if (isEventEnded || eventDetail === 'ERROR: No such event.') { // Hide going button
			modalContent.getElementsByClassName('going-btn')[0].classList.add('hidden');
		} else {
		modalContent.getElementsByClassName('input-event-name')[0].value = eventName;
		}
		// Inputs
		// if (isEventEnded || eventDetail === 'ERROR: No such event.') { // Hide going button
		// 	modalContent.getElementsByClassName('going-btn')[0].classList.add('hidden');
		// } else { // Show going button
		// 	modalContent.getElementsByClassName('input-event-name')[0].value = eventName;
		// 	var goingButton = modalContent.getElementsByClassName('going-btn')[0];
		// 	goingButton.classList.remove('hidden');
		// 	if (eventDetail['attendance']) { // Make going button blue
		// 		goingButton.classList.remove('btn-default');
		// 		goingButton.classList.add('btn-primary');
		// 		modalContent.getElementsByClassName('input-attendance')[0].value = true;
		// 	} else { // Make going button white
		// 		goingButton.classList.remove('btn-primary');
		// 		goingButton.classList.add('btn-default');
		// 		modalContent.getElementsByClassName('input-attendance')[0].value = false;
		// 	}
		// }
	};

	// Reference: https://stackoverflow.com/questions/22119673
	function findAncestor(el, cls) {
		while ((el = el.parentElement) && !el.classList.contains(cls));
		return el;
	}

	function eventWellHandler(e) {
		// Get event name
		var eventName;
		if (e.target.classList.contains('card-body')) {
			eventName = e.target.getElementsByTagName('h5')[0].textContent;
		} else if (e.target.tagName === 'P') {
			eventName = e.target.parentNode.getElementsByTagName('h5')[0].textContent;
		} else if (e.target.tagName === 'H5') {
			eventName = e.target.textContent;
		} else { // User didn't click a specific event card
			return;
		}

		// AJAX's GET request to retrieve details of this specific event
		var xhr = new XMLHttpRequest();
		xhr.open('GET', 'api.php?eventName='+eventName);
		xhr.onload = function() {
			if (xhr.status === 200) {
				var isEventEnded = findAncestor(e.target, 'tab-pane').id === 'past';
				setEventModal(eventName, JSON.parse(xhr.responseText), isEventEnded);
			} else {
				console.log('Request failed.  Returned status of ' + xhr.status);
			}
		};
		xhr.send();
	};

	// Assign a click event listener to the wrapper of event cards
	var eventsWrapper = document.getElementsByClassName('tab-content')[0];
	eventsWrapper.addEventListener('click', eventWellHandler);
} else if (filename === '/EventWeb/dashboard.php') {
    function setEventModal(eventName, attendees) {
        var modalContent = document.getElementsByClassName('modal-content')[0];
		modalContent.getElementsByClassName('modal-title')[0].textContent = eventName;
        
        console.log(document.getElementsByClassName('modal-content'));
        console.log(modalContent.getElementsByClassName('modal-title')[0].textContent);
        
		if (attendees === "ERROR: No such attendees/event.") {
            modalContent.getElementsByClassName('attendees-count')[0].textContent = 0;
			return;
		}
        modalContent.getElementsByClassName('attendees-count')[0].textContent = attendees.length;
        
        var ul = document.getElementById('attendees_list');
        var list = "";
        for (var i = 0; i < attendees.length; i++) {
            list += "<li class='list-group-item'>" + attendees[i]['name'] + "</li>";
        }
        ul.innerHTML = list;
	};

	function eventWellHandler(e) {
		// Get event name
		var eventName;
		if (e.target.classList.contains('well')) {
			eventName = e.target.getElementsByTagName('h4')[0].textContent;
		} else if (e.target.tagName === 'P') {
			eventName = e.target.parentNode.getElementsByTagName('h4')[0].textContent;
		} else if (e.target.tagName === 'H4') {
			eventName = e.target.textContent;
		} else { // User didn't click a specific event card
			return;
		}

		// AJAX's POST request to retrieve details of this specific event
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "api.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("eventAttendees=true&eventName="+eventName);

		xhr.onload = function() {
			if (xhr.status === 200) {
				setEventModal(eventName, JSON.parse(xhr.responseText));
			} else {
				console.log('Request failed.  Returned status of ' + xhr.status);
			}
		};
		
	};

	// Assign a click event listener to the wrapper of event cards
	var eventsWrapper = document.getElementsByClassName('tab-content')[0];
	eventsWrapper.addEventListener('click', eventWellHandler);
} else if (filename === '/EventWeb/signup.php') {
	// fix invalid email input conflict with signup form
	$(function() {
		$(".register_input").each(function() {
			changeState($(this));
		});

		$(".register_input").on("focusout", function() {
			changeState($(this));
		});

		function changeState($formControl) {
			if ($formControl.val().length > 0) {
				$formControl.addClass("has-value");
			} else {
				$formControl.removeClass("has-value");
			}
		}
	});
} else if (filename === '/EventWeb/changePassword.php') {
	/* 
		Switch actions
	*/
	$('.unmask').on('click', function(){
	
		if($(this).prev('input').attr('type') == 'password'){
			changeType($(this).prev('input'), 'text');
			$('.unmask').html("<i class='glyphicon glyphicon-eye-open'></i>");
		}
		
		else{
			changeType($(this).prev('input'), 'password');
			$('.unmask').html("<i class='glyphicon glyphicon-eye-close'></i>");
		}
		
		
		return false;
	});
  
  
	/* 
		function from : https://gist.github.com/3559343
		Thank you bminer!
	*/
  
  	function changeType(x, type) {
		if(x.prop('type') == type)
			return x; //That was easy.
		try {
			return x.prop('type', type); //Stupid IE security will not allow this
		} catch(e) {
			//Try re-creating the element (yep... this sucks)
			//jQuery has no html() method for the element, so we have to put into a div first
			var html = $("<div>").append(x.clone()).html();
			var regex = /type=(\")?([^\"\s]+)(\")?/; //matches type=text or type="text"
			//If no match, we add the type attribute to the end; otherwise, we replace
			var tmp = $(html.match(regex) == null ?
				html.replace(">", ' type="' + type + '">') :
				html.replace(regex, 'type="' + type + '"') );
			//Copy data from old element
			tmp.data('type', x.data('type') );
			var events = x.data('events');
			var cb = function(events) {
				return function() {
					//Bind all prior events
					for(i in events)
					{
						var y = events[i];
						for(j in y)
							tmp.bind(i, y[j].handler);
					}
				}
			}(events);
			x.replaceWith(tmp);
			setTimeout(cb, 10); //Wait a bit to call function
			return tmp;
		}
  	}
}

function AddBorder(num) { 
	if(num == 1)
	{
		document.getElementById('opt1').style.border = '4px solid black';
		document.getElementById('opt2').style.border = 'none';
		document.getElementById('opt3').style.border = 'none';
		document.getElementById('opt4').style.border = 'none';
		$("#submission").attr("disabled",false);
	}
	if(num == 2)
	{
		document.getElementById('opt1').style.border = 'none';
		document.getElementById('opt2').style.border = '4px solid black';
		document.getElementById('opt3').style.border = 'none';
		document.getElementById('opt4').style.border = 'none';
		$("#submission").attr("disabled",false);
	}
	if(num == 3)
	{
		document.getElementById('opt1').style.border = 'none';
		document.getElementById('opt2').style.border = 'none';
		document.getElementById('opt3').style.border = '4px solid black';
		document.getElementById('opt4').style.border = 'none';
		$("#submission").attr("disabled",false);
	}
	if(num == 4)
	{
		document.getElementById('opt1').style.border = 'none';
		document.getElementById('opt2').style.border = 'none';
		document.getElementById('opt3').style.border = 'none';
		document.getElementById('opt4').style.border = '4px solid black';
		$("#submission").attr("disabled",false);
	}
	}

function getOrg(user1, user2, userid, event, PartList, CartList) {
	var modalContent = document.getElementsByClassName('modal-content')[0];
	var Org = document.getElementById("EventOrganizer").innerHTML;
	var goingButton = modalContent.getElementsByClassName('going-btn')[0];
	if(user1 == user2) {
		document.getElementById('GoingButton').setAttribute('value', "Going");
		$("#GoingButton").attr("disabled",true);
		goingButton.classList.add('btn-primary');
		goingButton.classList.remove('btn-default');
	}
	else {
		var flag = 0;
		for (participants in PartList) {
			if(userid === PartList[participants].user_id && event === PartList[participants].event_id) {
				document.getElementById('GoingButton').setAttribute('value', "Going");
				$("#GoingButton").attr("disabled",true);
				goingButton.classList.add('btn-primary');
				goingButton.classList.remove('btn-default');
				flag = 1;
			}
		}

		if(flag == 0) {
			for (carts in CartList) {
				if(CartList[carts].user_id == Number(event) && CartList[carts].id == Number(userid)) {
					document.getElementById('GoingButton').setAttribute('value', "In Cart");
					$("#GoingButton").attr("disabled",true);
					goingButton.classList.add('btn-primary');
					goingButton.classList.remove('btn-default');
				}
			}
		}
		
		
	}
}
