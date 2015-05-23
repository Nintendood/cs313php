var request = [];
function getData(url)
{
	url = "getModifier.php?" + url;
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
		request.push(req);
	}
	else
	{
		alert("XMLHttp not supported on this browser!");
	}
}

function calculate()
{
	for (var i = 0; i < request.length; i++)
	{
		var req = request[i];
		if (req.readyState == 4)
		{
			if (req.status == 200)
			{
				var resp = req.responseText;
				var text = resp.split(",");
				request.splice(i, 1);
	
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
		defendType1 = stuff[i].innerHTML;
		defendType2 = stuff[i + 1].innerHTML;

		query = "attackType=" + attackType +
				"&defendType1=" + defendType1 +
				"&defendType2=" + defendType2 +
				"&modifierid=" + i;

		getData(query);
	}
}