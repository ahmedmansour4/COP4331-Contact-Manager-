var subdomain = "alex";

var urlBase = 'http://' + subdomain + '.aaaaaie.us';
var extension = 'php';
console.log(urlBase);
var userId = 0;
var firstName = "";
var lastName = "";



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

		window.location.href = "index.html";
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

		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		saveCookie();

		console.log("search.html?firstName=" + firstName + "&lastName=" + lastName);
		window.location.href = "search.html?firstName=" + firstName + "&lastName=" + lastName;
	}
	catch (err) {
		document.getElementById("loginResult").innerHTML = err.message;
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
		window.location.href = "index.html";
	}
	else {
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function doLogout() {
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addContact() {
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	var email = document.getElementById("email").value;
	var phoneNumber = document.getElementById("phoneNumber").value;
	var notes = document.getElementById("notes").value;

	document.getElementById("contactAddResult").innerHTML = "";

	var jsonPayload = '{"UID" : "' + userID + '", "firstName" : ' + firstName +
		'", "lastName" : ' + lastName + '", "email" : ' + email +
		'", "phoneNumber" : ' + phoneNumber + '", "notes" : ' + notes + '}';

	var url = urlBase + '/AddContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {
		document.getElementById("contactAddResult").innerHTML = err.message;
	}

}

function doSearch() {
	var srch = document.getElementById("searchBox").value;
	document.getElementById("colorSearchResult").innerHTML = "";

	var colorList = "";

	var jsonPayload = '{"UID" : "' + userID + '","search" : ' + srch + '}';
	var url = urlBase + '/SearchContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("contactSearchResult").innerHTML = "Contact(s) has been retrieved";
				var jsonObject = JSON.parse(xhr.responseText);

				for (var i = 0; i < jsonObject.results.length; i++) {
					contactList += jsonObject.results[i];
					if (i < jsonObject.results.length - 1) {
						contactList += "<br />\r\n";
					}
				}

				document.getElementsByTagName("p")[0].innerHTML = contactList;
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}

}