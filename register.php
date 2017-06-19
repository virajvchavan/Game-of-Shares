<html>
    <head>
        <title>Register | Game Of Shares</title>
        
        <meta name="description" content="Game of shares is a Share market game/Stock market game where users compete with each other to stay at the top of the leader board." />
        <meta name="keywords" content="stock market, share market, game, learn stocks, begginer" />
        <meta name="author" content="Viraj Chavan"/>
        <meta name="robots" content="index, follow" />
        
        <link type="text/css" href="css/login.css" rel="stylesheet">
        
        <style>
        /* Credit to bootsnipp.com for the css for the color graph */
        .colorgraph {
          height: 5px;
          border-top: 0;
          background: #c4e17f;
          border-radius: 5px;
          background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        }
            #link{
                margin-top: 10px;
                font-size: 18px;
                padding: 10px;
            }
        </style>
        
        <script>
            function validateForm()
                {
                    var pass1 = document.forms["register"]["password"].value;
                    var pass2 = document.forms["register"]["password_confirmation"].value;

                    if(pass1 != pass2)
                        {
                            alert("Passwords do not match.");
                            return false;
                        }
                    if(pass1.length < 6)
                        {
                            alert("Password must be at least 6 characters.");
                            return false;
                        }
                }


</script>
    </head>
    
    <body>
     
<div class="modal-dialog">
       <div class="modal-content">
          
           <div class="modal-body">
<div class="row">
    <div class="col-xs-12">
		<form role="form" method="post" action="help.php" name="register"  onsubmit="return validateForm()">
			<h2 class="text-center">Register</h2>
			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
                        <input type="text" name="first_name" id="first_name" class="form-control input-lg" placeholder="First Name" tabindex="1" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="last_name" id="last_name" class="form-control input-lg" placeholder="Last Name" tabindex="2" required>
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="4" required>
			</div>
            
            <div class="form-group">
				<input type="text" name="phone" id="phone" class="form-control input-lg" placeholder="Phone Number" tabindex="5">
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="6" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" tabindex="7" required>
					</div>
				</div>
			</div>
			
			<hr class="colorgraph">
			<div class="form-group">
                         <input type="submit" class="btn btn-block btn-lg btn-primary" value="Register"/>
                         <span class="pull-right" id="link"><a href="login.php">Login</a></span>
                         <br>
                         
                     </div>
		</form>
	</div>
</div>
           </div>
    </div>
</div>

    </body>
</html>