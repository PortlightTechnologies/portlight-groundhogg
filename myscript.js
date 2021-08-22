jQuery( document ).ready( function( $ ) {

	let notesDiv = document.getElementsByClassName('postbox info-card notes  ')[0];
	let btn = document.createElement("button");
	btn.innerHTML = "Print";
	btn.onclick = function () {
		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html><head><title>Notes</title>');
		mywindow.document.write('</head><body >');
		var notes = document.getElementsByClassName('display-notes gh-notes-container');
		for (var i=0, max=notes.length; i < max; i++) {
		  mywindow.document.write(notes[i].innerHTML);
		}
		mywindow.document.write('</body></html>');
		mywindow.document.close();
		mywindow.focus();
		mywindow.print();
		mywindow.close();
		return true;
	};
	notesDiv.appendChild(btn);
	let btnClear = document.createElement("button");
	btnClear.innerHTML = "Clear";
	btnClear.onclick = function () {
		var deleteButton = document.getElementsByClassName('delete-note delete danger');
		for (var i=0, max=deleteButton.length; i < max; i++) {
			 deleteButton[i].getElementsByTagName('a')[0].click();
		}
	};
	notesDiv.appendChild(btnClear);
		
	 $("#send-email").click(function(){
		  var email_content = $("#email_content_ifr").contents().find("#tinymce").html().replace(/<[^>]*>?/gm, '');
		  $("#add-new-note").val(email_content);
		  $("#add-note").click();
		});
		$("#sms").find(':submit').click(function(){
		  $("#add-new-note").val($("#sms-message").val());
		  $("#add-note").click();
		});	
		
} );