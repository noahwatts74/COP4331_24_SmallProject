var urlBase = 'http://cop4331-ucf-team24.xyz/LAMPAPI';
var extension = 'php';

var userId=0;
//var parent;
var firstName = "";
var lastName = "";
//var login = document.getElementById("loginName").value;
function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
	//parent=login;
	//var firstName = document.getElementById("firstName").value;
	//var lastName = document.getElementById("lastName").value;
	var hash = md5( password );
	document.cookie = login;
	document.getElementById("loginResult").innerHTML = "";
	jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
	//var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '", "firstName" : "' + firstName + '", "lastName" : "' + lastName + '"}';
//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Login.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse( xhr.responseText );
		//alert(xhr.responseText);	
		userId = jsonObject.id;
		//sessionStorage.setItem('userID', userId);

		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		saveCookie();
	
		window.location.href = "contacts.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}

function signup()
{
	userId = 0;
	firstName = "";
	lastName = "";

	var Login = document.getElementById("loginName").value;
	var Password = document.getElementById("loginPassword").value;
	var FirstName = document.getElementById("firstName").value;
	var LastName = document.getElementById("lastName").value;
	var passwordCheck = document.getElementById("pwdRepeat").value;
	if(passwordCheck != Password)
	{
		document.getElementById("loginResult").innerHTML = "Does not match given password";
	}

	var hash = md5(Password);
	
	document.getElementById("loginResult").innerHTML = "";

	//var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
	var jsonPayload = '{"Login" : "' + Login + '", "Password" : "' + hash + '", "FirstName" : "' + FirstName + '", "LastName" : "' + LastName + '"}';
//	var jsonPayload = '{"Login" : "' + login + '", "Password" : "' + password + '"}';
	var url = urlBase + '/Register.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse( xhr.responseText );
		//alert(xhr.responseText);	`
		userId = jsonObject.Login;
		
//		if userId does not equal any userId from database then good OR if userId equals a userId from database the error name already taken
//		if( document.getElementById("loginName").value != userId )
//		{
//			return;
//		} else {
//			document.getElementById("loginResult").innerHTML = "Username already taken";

		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;
		saveCookie();
	
		window.location.href = "index.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
	//document.cookie = "userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		//document.getElementById("userName").innerHTML = "Logged in as " + firstName+" "+lastName;
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addContact()
{
	//var ParentLogin = readCookie();]
	//var ParentLogin=userId;
	//var userId = document.cookie.readCookie().userId;
	//document.getElementById("userName").innerHTML = "Logged in as " + userId;
	var Parent = document.cookie.split(';')[0];
	var Phone = document.getElementById("phone").value;
	var Email = document.getElementById("email").value;
	var FirstName = document.getElementById("firstName").value;
	var LastName = document.getElementById("lastName").value;
	//var parentLogin=parent;
	document.getElementById("contactAddResult").innerHTML = "";
	var jsonPayload = '{"ParentLogin" : "' + Parent + '", "Phone" : "' + Phone + '", "FirstName" : "' + FirstName + '", "LastName" : "' + LastName + '", "Email" : "' + Email + '"}';
	//var jsonPayload = '{"ParentLogin" : "' + ParentLogin + '", "Phone" : ' + Phone + '}';
	var url = urlBase + '/AddContact.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
	location.reload();
	
}

function getContacts()
{
	var Parent = document.cookie.split(';')[0];
	var contactList = "";
	
	var jsonPayload = '{"ParentLogin" : "' + Parent + '"}';
	var url = urlBase + '/GetContacts.' + extension;
	//var parentLogin
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				//alert(xhr.responseText);
				var jsonObject = JSON.parse( xhr.responseText );
				
				for( var i=0; i<jsonObject.length; i++ )
				{
					contactList += `<tr id="row${i}"> <td id="firstname${i}" scope="col"> ${jsonObject[i]["FirstName"]}</td>`;
					contactList += `<td id="lastname${i}" scope="col"> ${jsonObject[i]["LastName"]}</td>`;
					contactList += `<td id="phone${i}" scope="col"> ${jsonObject[i]["Phone"]}</td>`;
					contactList += `<td id="email${i}" scope="col"> ${jsonObject[i]["Email"]}</td></tr>`;
					// <button class="btn btn-lg btn-primary btn-block text-uppercase" onclick="addContact();">Add new contact</button>
					//contactList += `<td id="actions${i}" scope="col"><button type="button" class="btn btn-sm btn-primary">Edit</button><button type="button" class="btn btn-sm btn-danger" >onclick="deleteContact();Delete</button></td></tr><span id="contactAddResult"></span>`;
					//contactDeleteResult
					//contactList += `<td id="actions${i}" scope="col"><button type="button" class="btn btn-sm btn-primary">Edit</button><button type="button" class="btn btn-sm btn-danger">Delete</button></td></tr>`;
				}
				
				document.getElementsByTagName("tbody")[0].innerHTML = contactList;
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
	
}
function deleteContact()
{
	var Parent = document.cookie.split(';')[0];
	var FirstName = document.getElementById("deleteFirstName").value;
	var LastName = document.getElementById("deleteLastName").value;
	var Phone = document.getElementById("deletePhone").value;
	
	document.getElementById("contactDeleteResult").innerHTML = "";
	var jsonPayload = '{"ParentLogin" : "' + Parent + '", "Phone" : "' + Phone + '", "FirstName" : "' + FirstName + '", "LastName" : "' + LastName + '"}';
	//var jsonPayload = '{"ParentLogin" : "' + Parent + '", "Phone" : "' + Phone + '", "FirstName" : "' + FirstName + '", "LastName" : "' + LastName + '"}';
	var url = urlBase + '/DeleteContact.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("contactDeleteResult").innerHTML = "Contact has been deleted";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactDeleteResult").innerHTML = err.message;
	}
	location.reload();
}
function editContact()
{
	//var ParentLogin = readCookie();]
	//var ParentLogin=userId;
	//var userId = document.cookie.readCookie().userId;
	//document.getElementById("userName").innerHTML = "Logged in as " + userId;
	var Parent = document.cookie.split(';')[0];
	var NewPhone = document.getElementById("newPhone").value;
	var NewEmail = document.getElementById("newEmail").value;
	var NewFirstName = document.getElementById("newFirstName").value;
	var NewLastName = document.getElementById("newLastName").value;
	var OriginalFirstName = document.getElementById("oldFirstName").value;
	var OriginalLastName = document.getElementById("oldLastName").value;
	var OriginalPhone = document.getElementById("oldPhone").value;
	//var parentLogin=parent;
	document.getElementById("contactAddResult").innerHTML = "";
	var jsonPayload = '{"ParentLogin" : "' + Parent + '", "OriginalPhone" : "' + OriginalPhone + '", "OriginalFirstName" : "' + OriginalFirstName + '", "OriginalLastName" : "' + OriginalLastName + '", "NewEmail" : "' + NewEmail + '", "NewFirstName" : "' + NewFirstName + '", "NewLastName" : "' + NewLastName + '", "NewPhone" : "' + NewPhone + '"}';
	//var jsonPayload = '{"ParentLogin" : "' + ParentLogin + '", "Phone" : ' + Phone + '}';
	var url = urlBase + '/UpdateUser.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("contactEditResult").innerHTML = "Contact has been edited";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactEditResult").innerHTML = err.message;
	}
	
}