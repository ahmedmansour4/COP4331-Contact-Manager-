

var subdomain = "alex";

var urlBase = 'http://' + subdomain + '.aaaaaie.us';
var extension = 'php';
console.log(urlBase);
var userId = 0;
var firstName = "";
var lastName = "";
var CID = 0;


document.getElementById("containInfo").style.display = "flex";

function doSignUp() {
	var email = document.getElementById("email").value;
	var userName = document.getElementById("userName").value;
	var passWord = document.getElementById("passWord").value;
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	console.log(email + " " + userName + " " + passWord + " " + firstName + " " + lastName);
	var jsonPayload = '{ "email" : "' + email + '", "userName" : "' + userName + '" , "passWord" : "' + passWord + '" , "firstName" : "' + firstName + '" , "lastName" : "' + lastName + '" }';

	var url = urlBase + '/api/signUp.' + extension;
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.send(jsonPayload);

		var jsonObject = JSON.parse(xhr.responseText);
		console.log(jsonObject.Mess);
		var message = jsonObject.Mess;

		if (message != "Success!") {
			document.getElementById("signupResult").innerHTML = message;
			return;
		}
		saveCookie();

		window.location.href = "../index.html";
	}
	catch (err) {
		return document.getElementById("signupResult").innerHTML = err;
	}
}

function doLogin() {
	userId = 0;
	firstName = "";
	lastName = "";

	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;

	document.getElementById("loginResult").innerHTML = "";

	var jsonPayload = '{"userName" : "' + login + '", "passWord" : "' + password + '"}';
	var url = urlBase + '/api/login.' + extension;
	// debugging
	console.log(url);
	console.log(jsonPayload);
	// end
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.send(jsonPayload);

		var jsonObject = JSON.parse(xhr.responseText);

		userId = jsonObject.UID;
		console.log(userId);
		if (userId < 1) {
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		
		console.log(jsonObject.UID)
		userId = jsonObject.UID;
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		saveCookie();

		console.log("search.html?firstName=" + firstName + "&lastName=" + lastName);
		window.location.href = "pages/search.html";
	}
	catch (err) {
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

function getCID()
{
	var firstName = document.getElementById("firstNameResult2").innerHTML;
	var lastName = document.getElementById("lastNameResult2").innerHTML;
	var phoneNumber = document.getElementById("phoneResult2").innerHTML;
	phoneNumber = phoneNumber.replace(/-/g,'');

	var jsonPayLoad = '{"UID" : "' + userID + '", "firstName" : "' + firstName + '", "lastName" : "' + lastName + '", "phoneNumber" : "' + phoneNumber + '"}';
	var url = urlBase + '/api/getCID.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
		xhr.send(jsonPayLoad);

		var jsonObject = JSON.parse( xhr.responseText );
		var message = jsonObject.Mess;

		if (message != "Success!")
		{
			alert("Could not update Contact");
			return;
		}

		CID = jsonObject.CID;
	}
	catch (err)
	{
		alert(err.message);
	}
}

function saveCookie() {
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime() + (minutes * 60 * 1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie() {
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for (var i = 0; i < splits.length; i++) {
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if (tokens[0] == "firstName") {
			firstName = tokens[1];
		}
		else if (tokens[0] == "lastName") {
			lastName = tokens[1];
		}
		else if (tokens[0] == "userId") {
			userId = parseInt(tokens[1].trim());
		}
	}
	
	if (userId < 0) {
		window.location.href = "../index.html";
	}
	
}

function doLogOut() {
	userId = 0;
	CID = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "../index.html";
}

function addContact() {
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	var email = document.getElementById("email").value;
	var phoneNumber = document.getElementById("phoneNumber").value;
	var notes = document.getElementById("notes").value;

	document.getElementById("addResult").innerHTML = "";

	var jsonPayload = '{"UID" : "' + userId + '", "firstName" : "' + firstName +
		'", "lastName" : "' + lastName + '", "email" : "' + email +
		'", "phoneNumber" : "' + phoneNumber + '", "notes" : "' + notes + '"}';

	var url = urlBase + '/api/addContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("addResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonPayload);
		getContacts();
	}
	catch (err) {
		document.getElementById("addResult").innerHTML = err.message;
	}
	getContacts();
	location.reload();
}

function doSearch() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchBox");
  filter = input.value.toUpperCase();
  table = document.getElementById("contactsList");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
  var abc = 0;
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) == 0) {
        abc = 1;
      } else {
       // tr[i].style.display = "none";
      }
    } 
    td1 = tr[i].getElementsByTagName("td")[1];
    if (td1) {
      txtValue = td1.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) == 0) {
        abc = 1;
      } else {
        //tr[i].style.display = "none";
      }
    }
    if (abc == 1) {
        tr[i].style.display = "";
      }
      else
      {
      tr[i].style.display = "none";
      }
  }}

function getContacts()
{
	readCookie();
	console.log(userId);

	var jsonPayload = '{"UID" : "' + userId + '"}';
	var url = urlBase + '/api/searchContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var jsonObject = JSON.parse(xhr.responseText);
				console.log(jsonObject.results);
				var table = document.getElementById("contactsList");
				
				
				console.log(jsonObject.results.length);
				for (var i = 0; i < jsonObject.results.length; i++) {
					var row = table.insertRow();
					var splitArr = jsonObject.results[i].split(" ");
					
					for (var j = 0; j < 4; j++) {
						row.insertCell(j).innerHTML = splitArr[j];
					}

					var notes = ""
					for (var j = 4; j < splitArr.length; j++) {
						notes += splitArr[j];
						notes += " ";
					}
					row.insertCell(4).innerHTML = notes;
					
					
				}
				var rows = table.rows;
				for (var i = 0; i < rows.length; i++) {
					rows[i].cells[2].style.display = "none";
					rows[i].cells[3].style.display = "none";
					rows[i].cells[4].style.display = "none";
				}

				addRowHandlers();
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {
		document.getElementById("contactsList").innerHTML = err.message;
	}

}

function deleteContact()
{
	var firstName = document.getElementById("firstNameResult2").innerHTML;
	var lastName = document.getElementById("lastNameResult2").innerHTML;

	var jsonPayload = '{"UID" : "' + userId + '", "firstName" : "' + firstName +
		'", "lastName" : "' + lastName + '"}';

	console.log(jsonPayload);

	var url = urlBase + '/api/deleteContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				//document.getElementById("Result").innerHTML = "Contact has been deleted";
			}
		};
		xhr.send(jsonPayload);
		getContacts();
	}
	catch (err) {
		console.log(err.message);
	}
	location.reload();
}

function setInfo(fn, ln, pr, em, nt)
{
	document.getElementById("firstNameResult").innerHTML = fn;
	document.getElementById("lastNameResult").innerHTML = ln;
	document.getElementById("phoneResult").innerHTML = pr;
	document.getElementById("emailResult").innerHTML = em;
	document.getElementById("notesResult").innerHTML = nt;
	
	// Info for delete window
	document.getElementById("firstNameResult2").innerHTML = fn;
	document.getElementById("lastNameResult2").innerHTML = ln;
	document.getElementById("phoneResult2").innerHTML = pr;
	document.getElementById("emailResult2").innerHTML = em;
}

function addRowHandlers() {
    var table = document.getElementById("contactsList");
    var rows = table.getElementsByTagName("tr");
    for (i = 0; i < rows.length; i++) {
        var currentRow = table.rows[i];
        var createClickHandler = 
            function(row) 
            {
                return function() { 
                                        var fn = row.getElementsByTagName("td")[0].innerHTML;
					var ln = row.getElementsByTagName("td")[1].innerHTML;
					var pn = row.getElementsByTagName("td")[2].innerHTML;
					var em = row.getElementsByTagName("td")[3].innerHTML;
					var nt = row.getElementsByTagName("td")[4].innerHTML;
					
					setInfo(fn, ln, pn, em, nt);
                                        
                                 };
            };

        currentRow.onclick = createClickHandler(currentRow);
    }
}


function editContact()
{
	getCID();

	var firstName = document.getElementById("editFirstName").value;
	var lastName = document.getElementById("editLastName").value;
	var email = document.getElementById("editEmail").value;
	var phoneNumber = document.getElementById("editPhoneNumber").value;
	var notes = document.getElementById("editNotes").value;

	document.getElementById("addResult").innerHTML = "";

	var jsonPayload = '{"UID" : "' + userId + '", "firstName" : "' + firstName +
		'", "lastName" : "' + lastName + '", "email" : "' + email +
		'", "phoneNumber" : "' + phoneNumber + '", "notes" : "' + notes + '"}';

	var url = urlBase + '/api/addContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("addResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonPayload);
		getContacts();
	}
	catch (err) {
		document.getElementById("addResult").innerHTML = err.message;
	}
	getContacts();

	location.reload();
}

