<!doctype html>
<html ng-app="Login_Page">
  <head>
    <link rel = "stylesheet" type = "text/css" href = "/Quick/css/login.css">
    <link rel = "stylesheet" type = "text/css" href = "/Quick/css/modal.css">
    <script src="/Quick/js/angular.min.js"></script>
    <script src="/Quick/js/main.js"></script>
    <title>Login Page</title>
  </head>
    <body class = "body">
      <div class = "login-container" ng-controller = "LoginCtrl">
        <section>
        <div class = "header"></div>
        </section>
        <section>
            <div class = "login-box">
                <div class = "input-box">
                  <section class = "line-spacing-10">
                    <center><input class = "input-text" type = "text" ng-model = "username" placeholder = "Username" required></center>
                  </section>
                  <section class = "line-spacing-15">
                    <center><input class = "input-text" type = "password" ng-model = "password" placeholder = "Password" required></center>
                  </section>
                </div> 
                <div class = "login-box-bottom">
                  <section class = "remember-me" >
                    <input type = "checkbox" ng-model = "remember"> Remember me
                  </section>
                  <section>
                    <div class = "signup" ng-click = "showSignUp = !showSignUp"></div>
                  </section>
                </div>
                <div class = "login-button" ng-click = "login()"></div>
            </div>

            <!-- login error message -->
            <error-container show='loginError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Login Error!</b><br></center>
                        Invalid <i>username/password</i>.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>
            <!-- sign up modal -->
            <sign-up-container show = "showSignUp" width = "320px" height = "320px">
                  <section>
                    <div class = "close-button" ng-click = "closeSignUp()"></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "text" ng-model = "firstname" placeholder  = "First Name...." maxlength = "50" required></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "text" ng-model = "lastname" placeholder  = "Last Name" maxlength = "50" required></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "text" ng-model = "middlename" placeholder  = "Middle Name" maxlength = "50" required></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "text" ng-model = "username1" placeholder  = "Username" maxlength = "50" required></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "password" ng-model = "password1" placeholder  = "Password" maxlength = "20" required></div>
                  </section>
                  <section>
                    <div><input class = "input-class" type = "password" ng-model = "vpassword" placeholder  = "Verify Password" maxlength = "50" required></div>
                  </section>
                  <section>
                    <div ng-click = "signUp(firstname, lastname, middlename, username1, password1, vpassword)"><button class = "register" >Sign Up</button></div>
                  </section>
            </sign-up-container>

            <sign-up-container show = "showSuccess" width = "320px" height = "185px">
                 <!-- <div class = "success-body" -->
                    <div class = "success-header">
                        <div class = "success-icon"></div>
                    </div>
                    <div class = "signup-message">
                        <center><b>Successful Sign Up!</b><br></center>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                <!-- </div> -->
            </sign-up-container>
            <!-- //firsname error message -->
            <error-container show='fNameError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Firstname</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- lname error -->
            <error-container show='lNameError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Last name</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- mNameError -->
            <error-container show = 'mNameError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Middle name</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- uname Error -->
            <error-container show = 'uNameError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Username</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- password Error -->
            <error-container show='passwordError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Password</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- vpassword Error -->
            <error-container show='vPasswordError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Password</i> doesn't match.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

            <!-- already used -->
            <error-container show='alreadyUsed' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Signup Error!</b><br></center>
                        <i>Username</i> already used.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container>

        </section>
      </div>
      <div class = "footer">
        Copyright &copy; 2014 Ybanez-Nacua Clan.<br/>
        All Rights Reserved.
      </div>
    </body>
</html>