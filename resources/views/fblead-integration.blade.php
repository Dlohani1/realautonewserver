<html>
<head> 
<title> Fb Lead Integration</title>
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=3312603435483167&autoLogAppEvents=1" nonce="PwoHvEZv"></script>
<!-- Submitted March 7 @ 11:05pm  -->
<div class="container">
<div class="row">
    <div class="col-md-4">
        <img src="https://pickyassist.com/en/img/facebook-leads/banner-img.png" class="img-responsive center-block" alt="">
    </div><!--.col -->
    <div class="col-md-8">
        <h3>
           Integrate Leads from Facebook
        </h3>


<!-- NOTE: TB3 form width default is 100%, so they expan to your <div> -->
       
           
           <div class="fb-login-button" data-width="" data-size="medium" data-button-type="continue_with" data-layout="rounded" data-auto-logout-link="false" 
data-use-continue-as="false" data-scope="pages_read_engagement,pages_manage_metadata,pages_manage_ads,email,pages_show_list,leads_retrieval,public_profile" 
onlogin="checkLoginState();"></div>

        <hr>
        <p>
          Pages List
        </p>

        <Select id="pages-list" class="form-control" rows="3" onchange = "subscribePage()">
        
	<option>Select Pages to Subscribe</option>
	</Select>
        <hr>
        <p style="display:none">
            Forms List 
        </p>
        <input hidden type="text" placeholder="page access token" id="pageaccesstoken" />
<input type="text" placeholder="page name" id="pagename" hidden />

<input type="text" placeholder="page id" id="pageid" hidden/>
        
          <Select style="display:none" id="form-list" class="form-control" rows="3">
        </Select>
        <br><br>
        <button type="button" class="btn btn-primary" onclick="showData()">Integrate Leads</button>
</div><!--./row -->
</div><!--./container -->
<!--
<input type="text" placeholder="page access token" id="pageaccesstoken" />
<input type="text" placeholder="page name" id="pagename" />

<input type="text" placeholder="page id" id="pageid" />


<input type="text" placeholder="lead gen id" id="leadgenid" />
-->
<button type="button" onclick = "showData()" hidden>Show Data </button>

<h2 style="display:none">FB lead Integration Platform</h2>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '3312603435483167',
      xfbml      : true,
      version    : 'v11.0'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


  function subscribePage() {

	var pageData = document.getElementById("pages-list").value;
    const data = pageData.split("|");
	var page_id = data[0]
	var page_access_token = data[1] 
     document.getElementById("pageaccesstoken").value = page_access_token;
     document.getElementById("pageid").value = page_id;
var e = document.getElementById("pages-list");

var text=e.options[e.selectedIndex].text; 
     document.getElementById("pagename").value = text;

    console.log('Subscribing page to app! ' + page_id);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['leadgen','name','phone']},
      function(response) {
        console.log('Successfully subscribed page', response);
	alert('Successfully Subscribed Page');
      }
    );
}



  function subscribeApp(page_id, page_access_token) {

//	document.getElementById("pageaccesstoken").value = page_access_token;

    console.log('Subscribing page to app! ' + page_id);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['leadgen','name','phone']},
      function(response) {
        console.log('Successfully subscribed page', response);
      }
    );

/*

FB.api(
    '/'+page_id+'/leadgen_forms',
    'get',
      {access_token: page_access_token},

    function (response) {
	console.log('rs',response)

var url = "https://www.facebook.com/ads/lead_gen/export_csv/?id="+response.data[0].id+"&type=form&from_date=1482698431&to_date=1482784831";

var xhr = new XMLHttpRequest();
xhr.open("GET", url);

xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      console.log(xhr.status);
      console.log(xhr.responseText);
   }};

xhr.send();


var url = "https://graph.facebook.com/v11.0/"+response.data[0].id+"/leads";

var xhr = new XMLHttpRequest();
xhr.open("POST", url);

xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      console.log(xhr.status);
      console.log(xhr.responseText);
   }};

var data = "access_token="+page_access_token+"&fields=created_time,id,ad_id,form_id,field_data";

xhr.send(data);


	
      if (response && !response.error) {
        
      }
    }
);
*/
  }
    
  // Only works after `FB.init` is called
  function myFacebookLogin() {
    FB.login(function(response){
      console.log('Successfully logged in', response);
 document.getElementById("pageaccesstoken").value = response.authResponse.accessToken;
      FB.api('/me/accounts', function(response) {

        console.log('Successfully retrieved pages', response);
        var pages = response.data;
       /* var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
        }
*/
      });
    }, {scope: 'pages_read_engagement,pages_manage_metadata,pages_manage_ads,email,pages_show_list,leads_retrieval,public_profile',return_scopes: true});
  }


function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
     // myFacebookLogin(); 
document.getElementById("pageaccesstoken").value = response.authResponse.accessToken;
      FB.api('/me/accounts', function(response) {

        console.log('Successfully retrieved pages', response);
        var pages = response.data;


$.each(pages , function (key, value) {


                                $('#pages-list').append($('<option>',
                                    {
                                    value: value.id+"|"+value.access_token,
                                    text: value.name
                                }));


});
        var ul = document.getElementById('list');

        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
        }
      });

 
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this webpage.';
    }
  }


  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }

function showData() {

/*var leadId = document.getElementById("leadgenid").value;
var token = document.getElementById("pageaccesstoken").value;
FB.api(
    "/"+leadId,
 'get',
      {access_token: token},
    function (response) {
console.log("finalres",response)

      if (response && !response.error) {
        
      }
    }
);
*/
$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

var pageName = document.getElementById("pagename").value;

var pageId = document.getElementById("pageid").value;

var pageToken = document.getElementById("pageaccesstoken").value;

//var formData = {_token: "{{ csrf_token() }}",page_name:pageName,page_token:pageToken,page_id:pageId};
var formData = new FormData();
formData.append('page_name', pageName);
formData.append('page_token', pageToken);
formData.append('page_id', pageId);

$.ajax({
			type:'POST',
			url: "{{ url('subscribe-page')}}",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
				//document.getElementById("uploadMsg").innerHTML="File Uploaded Successfully";
				$('#uploadMsg').delay(5000).fadeOut('slow');
				document.getElementById("attachment").value=baseUrl+"/"+data.filepath
				
				//alert(document.getElementById("attachment").value)
				console.log('d',data.filepath)
				//this.reset();
				//alert('File has been uploaded successfully');
				//console.log(data);
			},
			error: function(data){
				console.log(data);
			}
		});

}
</script>
<!-- <button onclick="myFacebookLogin()">Login with Facebook</button> 
<fb:login-button class="fb_button" length="long" size="large" scope="pages_read_engagement,pages_manage_metadata,pages_manage_ads,email,pages_show_list,leads_retrieval,public_profile" 
onlogin="checkLoginState();">
    <span style= "margin-right: 10px;">Connect with Facebook</span>
</fb:login-button>

<fb:login-button scope="pages_read_engagement,pages_manage_metadata,pages_manage_ads,email,pages_show_list,leads_retrieval,public_profile" onlogin="checkLoginState();">
</fb:login-button>
<span id="status"></span>
<ul id="list"></ul>
-->
</body>
</html>
