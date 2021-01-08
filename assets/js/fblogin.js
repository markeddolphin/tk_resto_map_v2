
  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

function statusChangeCallback(response) {    
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      fbResultAPI();
      console.debug("login OK");      
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      console.debug("please login");
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      console.debug("please login");
    }
}


  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : $("#fb_app_id").val(),//'',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.4' // use version 2.0
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  /*FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });*/

  };

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function fbResultAPI() {  	
    FB.api('/me?fields=email,first_name,last_name', function(response) {      
      console.debug(response);           
      fb_register(response);  
    });
  }
  
  function fbLogout()
  {
  	 FB.logout(function(response) {
        console.debug("Person is now logged out");
     });
  } 
  
  
 /* function fbLogoutUser() {
    FB.getLoginStatus(function(response) {
        if (response && response.status === 'connected') {
            FB.logout(function(response) {
                document.location.reload();
            });
        }
    });
}*/
 
 function fbcheckLogin()
 {
 	FB.login(function(response) {
 		dump(response);
 		if (response.status === 'connected') {
 			uk_msg_sucess(js_lang.login_succesful);
 		    fbResultAPI();
 		} else if (response.status === 'not_authorized') {
 			uk_msg(js_lang.not_authorize);
 		} else {
 			uk_msg(js_lang.not_login_fb);
 		}
 	}, {scope: 'public_profile,email'});
 }