function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
if( document.getElementById('firstName').innerHTML != null &&
	document.getElementById('lastName').innerHTML !=null)
{
	document.getElementById('firstName').innerHTML= getUrlVars()["firstName"];
	document.getElementById('lastName').innerHTML= getUrlVars()["lastName"];

}
