<html>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=3312603435483167&autoLogAppEvents=1" nonce="gPs2ISAF"></script>


<div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false"></div>

<button type="button" onclick="openfb()">Login </button>

<script>
function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
console.log('conne')
//   testAPI();  
    } else {
console.log("please login")                                 // Not logged into your webpage or we are unable to tell.
     // document.getElementById('status').innerHTML = 'Please log ' +
   //     'into this webpage.';
    }
  
}

/*
window.fbAsyncInit = function() {
    FB.init({
      appId      : '3312603435483167',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v11.0'           // Use this Graph API version for this call.
    });

FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
}
*/

function openfb() { 

FB.login(function(response) {
  if (response.status === 'connected') {
	console.log('res',response)
    // Logged into your webpage and Facebook.
  } else {
    // The person is not logged into your webpage or we are unable to tell. 
  }
});
var url = "https://www.facebook.com/v11.0/dialog/oauth?client_id=3312603435483167&redirect_uri=https://realauto.in/fb-webhook&state=st=state123abc,ds=123456789";
  
//var url = "https://www.facebook.com/v11.0/dialog/oauth?client_id=3312603435483167&redirect_uri=https://www.google.com&state={st=state123abc,ds=123456789}"
  
window.open(url,"_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");


}  
  </script>

</body>
</html>
