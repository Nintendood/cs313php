//////////////////////////////////////////////
// GET DATA
//////////////////////////////////////////////

var getRequest = [];
function getData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = calculate;
		req.open("GET", url, true);
		req.send(null);
		getRequest.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function calculate()
{
	for (var i = 0; i < getRequest.length; i++)
	{
		var req = getRequest[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				var text = resp.split(",");
				getRequest.splice(i, 1);
	
				document.getElementById("modifier" + text[0]).innerHTML = text[1];
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function buildQuery()
{
	attackType = document.getElementById("attackType").value;
	stuff = document.getElementsByClassName("stuff");
	for (var i = 0; i < stuff.length; i += 2)
	{
		defendType1 = document.getElementById("defendType1" + i).value;
		defendType2 = document.getElementById("defendType2" + i).value;

		query = "dbInteract.php?op=get" +
				"&attackType=" + attackType +
				"&defendType1=" + defendType1 +
				"&defendType2=" + defendType2 +
				"&modifierid=" + i;

		getData(query);
	}
}

//////////////////////////////////////////////
// SAVE DATA
//////////////////////////////////////////////
var saveRequest = [];

function getSaveData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = saveToDB;
		req.open("GET", url, true);
		req.send(null);
		saveRequest.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function saveToDB()
{
	for (var i = 0; i < saveRequest.length; i++)
	{
		var req = saveRequest[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;				
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function buildSaveData()
{
	stuff = document.getElementsByClassName("stuff");
	for (var i = 0; i < stuff.length; i += 2)
	{
		name = document.getElementById("name" + i).value;
		id = document.getElementById("pokemonID" + i).innerHTML;
		defendType1 = document.getElementById("defendType1" + i).value;
		defendType2 = document.getElementById("defendType2" + i).value;

		query = "dbInteract.php?op=save"+
				"&name=" + name +
				"&id=" + id +
				"&defendType1=" + defendType1 +
				"&defendType2=" + defendType2;

		getSaveData(query);
	}
}

//////////////////////////////////////////////
// ADD POKEMON
//////////////////////////////////////////////
var addRequest = [];

function getAddData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = addToDB;
		req.open("GET", url, true);
		req.send(null);
		addRequest.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function addToDB()
{
	for (var i = 0; i < addRequest.length; i++)
	{
		var req = addRequest[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function buildAddData()
{
		partyId = document.getElementById("partyId").innerHTML;

		query = "dbInteract.php?op=add" + 
				"&partyId=" + partyId;

		getAddData(query);
		location.reload();
}

//////////////////////////////////////////////
// DELETE POKEMON
//////////////////////////////////////////////
var deleteRequest = [];

function getDeleteData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = deleteFromDB;
		req.open("GET", url, true);
		req.send(null);
		deleteRequest.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function deleteFromDB()
{
	for (var i = 0; i < deleteRequest.length; i++)
	{
		var req = deleteRequest[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function buildDeleteData(id)
{
	query = "dbInteract.php?op=delete" + 
			"&id=" + id;

	getDeleteData(query);
	location.reload();
}

//////////////////////////////////////////////
// ADD PARTY
//////////////////////////////////////////////
var addParty = [];

function getAddPartyData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = addPartyToDB;
		req.open("GET", url, true);
		req.send(null);
		addParty.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function addPartyToDB()
{
	for (var i = 0; i < addParty.length; i++)
	{
		var req = addParty[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				alert(resp);
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function addPartyData(userId)
{
	var partyName = prompt("New Party Name", "");

	query = "dbInteract.php?op=addParty" + 
			"&name=" + partyName +
			"&userId=" + userId;
	getAddPartyData(query);
	location.reload();
}

//////////////////////////////////////////////
// DELETE PARTY
//////////////////////////////////////////////
var deleteParty = [];

function getDeletePartyData(url)
{
	var req;

	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req != null)
	{
		req.onreadystatechange = deletePartyFromDB;
		req.open("GET", url, true);
		req.send(null);
		deleteParty.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function deletePartyFromDB()
{
	for (var i = 0; i < deleteParty.length; i++)
	{
		var req = deleteParty[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				return;
			}
			else
			{
				alert("Status: " + req.status);
				return false;
			}
		}
	}
}

function deletePartyData(id)
{
	var r = confirm("Are you sure you want to delete this party?");
	if (r == true) {
		query = "dbInteract.php?op=deleteParty" + 
				"&id=" + id;
		getDeletePartyData(query);
		location.reload();
	} 
	else {
	}
}