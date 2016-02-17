(function(window, angular)
{
    var taskManagement = angular.module("Login_Page", []);
    var taskManagementHome = angular.module("Home_Page", []);

    LoginCtrl.$injector = ['$scope', '$http'];
    HomeCtrl.$injector = ['$scope', '$http'];
   /* Save.$injector = ['$scope', '$http'];*/

    //controllers
    taskManagement.controller('LoginCtrl', LoginCtrl);
    taskManagementHome.controller('HomeCtrl', HomeCtrl); 

    //modals
    taskManagement.directive('errorContainer', errorContainer);
    taskManagement.directive('signUpContainer', signUpContainer);
    taskManagementHome.directive('notifContainer', notifContainer);
    taskManagementHome.directive('container', container);
    taskManagementHome.directive('errorContainerHome', errorContainerHome);

    function LoginCtrl($scope, $http)
    {
        $scope.loginError = false;
        var url = "/Quick/response/responseList.php";
        var data = {request: "checkIflogged"};

        //check if user already login
        $http.put(url, {data : data})
        .success(function(res)
                {
                    if(res.status)
                        window.location = "/Quick/views/home.php";
                    else
                    {
                        var data = {request: "getCookieValues"};
                        $http.put(url, {data:data})
                        .success(   function(res)
                                    {
                                        if(res.status)
                                        {
                                            $scope.username = res.data['username'];
                                            $scope.password = res.data['password'];
                                            $scope.remember = true;
                                        }
                                    });
                        $scope.login =  function()
                                        {
                                            var rem = false;

                                            if($scope.remember === true)
                                                rem = true;
                                            var userInput = {
                                                                username : $scope.username,
                                                                password : $scope.password,
                                                                request  : "login",
                                                                remember : rem
                                                            };
                                            $http.put(url, {data : userInput})
                                            .success(   function(res)
                                                        {
                                                            if(res.status)
                                                            {
                                                                $scope.errorHolder = "";
                                                                window.location = "/Quick/views/home.php";
                                                            }
                                                            else
                                                            {
                                                                $scope.loginError = true;
                                                                $scope.password = "";
                                                            }
                                                        }   
                                                    );
                                        };
                                        $scope.closeSignUp =    function()
                                                                {
                                                                    $scope.showSignUp = false;
                                                                };
                                        $scope.signUp =     function(fname, lname, mname, username, password, vpassword)
                                                            {
                                                                if(angular.isUndefined(fname) || fname == null)
                                                                    $scope.fNameError = true;
                                                                else if(angular.isUndefined(lname) || lname == null)
                                                                    $scope.lNameError = true;
                                                                else if(angular.isUndefined(mname) || mname == null)
                                                                    $scope.mNameError = true;
                                                                else if(angular.isUndefined(username)|| username == null)
                                                                    $scope.uNameError = true;
                                                                else if(angular.isUndefined(password) || password == null)
                                                                    $scope.passwordError = true;
                                                                else if(angular.isUndefined(vpassword) || vpassword == null)
                                                                    $scope.vPasswordError = true;
                                                                else
                                                                {
                                                                    var data =  { firstname : fname,
                                                                                  lastname : lname,
                                                                                  middlename : mname,
                                                                                  username : username,
                                                                                  password : password,
                                                                                  request : "signUp"
                                                                                };
                                                                    $http.put(url, {data : data})
                                                                    .success(   function(res)
                                                                                {
                                                                                    if(!res.status)
                                                                                        $scope.alreadyUsed = true;
                                                                                    else
                                                                                    {
                                                                                         $scope.showSignUp = false;
                                                                                         $scope.showSuccess = true;
                                                                                    }
                                                                                });
                                                                }
                                                            };
                                        $scope.closeMessage =   function()
                                                                {
                                                                    $scope.loginError = false;
                                                                    $scope.fNameError = false;
                                                                    $scope.lNameError = false;
                                                                    $scope.mNameError = false;
                                                                    $scope.uNameError = false;
                                                                    $scope.showSuccess      = false;
                                                                    $scope.passwordError    = false;
                                                                    $scope.vPasswordError   = false;
                                                                    $scope.alreadyUsed      = false;
                                                                }
                    }
                }  
            );
    }
    function HomeCtrl($scope, $http)
    {        
        var url = "/Quick/response/responseList.php";
        
        $scope.unreadNotifs = "";
        $scope.showNoProject = false;
        $scope.showNoTask = false;
        $scope.showDeleteProject = false;

        var url = "/Quick/response/responseList.php";
        var data = {request: "checkIflogged"};

        //check if user already login
        $http.put(url, {data : data})
        .success(function(res)
                {
                    if(!res.status)
                        window.location = "/Quick/views/login.php";
                    else
                    {
                        //get userinfo
                        var data = { request : "getUserInfo"};
                        $http.put(url, {data : data})
                        .success(function(res)
                                    {
                                        if(res.status)
                                            $scope.userInformation = res.data;
                                    }   
                                );
                        //get priorityTypes
                        var data = {request: "getPriorityTypes"};
                        $http.put(url, {data: data})
                        .success(   function(res)
                                    {
                                        if(res.status)
                                            $scope.priorityTypes = res.data;
                                    }
                                );
                        //get statusTypes for add
                        var data = {request : "getStatusTypesAdd"};
                        $http.put(url, {data: data})
                        .success(   function(res)
                                    {
                                        if(res.status)
                                            $scope.statusTypesAdd = res.data;
                                    }
                                );

                        //get statusTypes for edit
                        var data = {request: "getStatusTypes"};
                        $http.put(url, {data: data})
                        .success(   function(res)
                                    {
                                        if(res.status)
                                            $scope.statusTypes = res.data;
                                    }
                                );
                    }
                });
        $scope.getProjects =    function()
                                {
                                     //get projects
                                    var data = { request : "getProjects"};

                                    $http.put(url, {data: data})
                                    .success(   function(res)
                                                {
                                                    if(res.status)
                                                    {
                                                        $scope.projects = res.data;
                                                        $scope.showNoProject = false;
                                                    }
                                                    else
                                                    {
                                                        $scope.showNoProject = true;
                                                        $scope.noProject = "No Project. Create one by clicking the add icon above.";
                                                    }
                                                });
                                    //get project task
                                    var data = { request : "getProjectTask"};

                                    $http.put(url, {data: data})
                                    .success(   function(res)
                                                {
                                                    if(res.status)
                                                    {
                                                        $scope.projectTasks = res.data;
                                                        $scope.showNoTask = false;
                                                    }
                                                    else
                                                    {
                                                        $scope.showNoTask = true;
                                                        $scope.noTask = "No Task. Create one by clicking the add icon above.";
                                                    }
                                                });

                                };
        $scope.getTasks =   function()
                            {
                                 //get tasks
                                var data = { request : "getTasks"};

                                $http.put(url, {data: data})
                                .success(   function(res)
                                            {
                                                if(res.status)
                                                {
                                                    $scope.tasks = res.data;
                                                    $scope.showNoTask = false;
                                                }
                                                else
                                                {
                                                    $scope.showNoTask = true;
                                                    $scope.noTask = "No Task. Create one by clicking the add icon above.";
                                                }
                                            });
                            };
        //send request to logout user
        $scope.logout = function()
                        {
                            var data = { request : "logout"}
                            $http.put(url, {data : data})
                            .success(function(res)
                                        {
                                            if(res.status)
                                                window.location = "login.php";
                                        }   
                                    );
                        };
        //initialize showNotif to true if click
        $scope.notif =  function()
                        {
                            $scope.showNotif = !$scope.showNotif;
                            //search here if there are notifcations
                        };
        $scope.closeModal = function()
                            {
                                $scope.showAddModal = false;
                            };
        $scope.add =    function(names, prior, stat, desc, types)
                        {
                            if(angular.isUndefined(names) || names == null)
                                $scope.nameError = true;
                            else if(angular.isUndefined(prior) || prior == null)
                                $scope.priorError = true;
                            else if(angular.isUndefined(stat) || stat == null)
                                $scope.statError = true;
                            else if(angular.isUndefined(desc)|| desc == null)
                                $scope.descError = true;
                            else if(angular.isUndefined(types) || types == null)
                                $scope.typesError = true;
                            else
                            {
                                if(types === "Project")
                                {
                                    $scope.showNoProject = false;
                                    $scope.showTasks = false;
                                    $scope.showProjects = true;
                                }
                                else
                                {
                                    $scope.showNoTask = false;
                                    $scope.showProjects = false;
                                    $scope.showTasks = true;
                                }

                                var data =  {
                                                name: names,
                                                priority: prior,
                                                status: stat,
                                                description: desc,
                                                type: types,
                                                projectId: 0,
                                                request: "save"
                                            };
                                $http.put(url, {data: data})
                                .success(   function(res)
                                            {
                                                if(res.status)
                                                {
                                                    $scope.closeModal();
                                                    //get projects
                                                    var data = { request : "getProjects"};

                                                    $http.put(url, {data: data})
                                                    .success(   function(res)
                                                                {
                                                                    if(res.status)
                                                                        $scope.projects = res.data;
                                                                    else
                                                                    {
                                                                        $scope.showNoProject = true;
                                                                        $scope.noProject = "No Project. Create one by clicking the add icon above.";
                                                                    }
                                                                });
                                                    //get tasks
                                                    var data = { request : "getTasks"};

                                                    $http.put(url, {data: data})
                                                    .success(   function(res)
                                                                {
                                                                    if(res.status)
                                                                        $scope.tasks = res.data;
                                                                    else
                                                                    {
                                                                        $scope.showNoTask = true;
                                                                        $scope.noTask = "No Task. Create one by clicking the add icon above.";
                                                                    }
                                                                });
                                                }
                                            });
                            }
                        };
        $scope.addTask =    function(names, prior, stat, desc, types, parentId)
                            {
                                if(angular.isUndefined(names) || names == null)
                                    $scope.nameError = true;
                                else if(angular.isUndefined(desc)|| desc == null)
                                    $scope.descError = true;
                                else if(angular.isUndefined(prior) || prior == null)
                                    $scope.priorError = true;
                                else if(angular.isUndefined(stat) || stat == null)
                                    $scope.statError = true;
                                else
                                {
                                    $scope.showNoTask = false;

                                    var data =  {
                                                    name: names,
                                                    priority: prior,
                                                    status: stat,
                                                    description: desc,
                                                    type: types,
                                                    projectId: parentId,
                                                    request: "save"
                                                };
                                    $http.put(url, {data: data})
                                    .success(function(res)
                                    {
                                        if(res.status)
                                        {
                                            var data = { request : "getProjects"};

                                            $http.put(url, {data: data})
                                            .success(   function(res)
                                                        {
                                                            if(res.status)
                                                                $scope.projects = res.data;
                                                            else
                                                            {
                                                                $scope.showNoProject = true;
                                                                $scope.noProject = "No Project. Create one by clicking the add icon above.";
                                                            }
                                                        });
                                            //get tasks
                                            var data = { request : "getProjectTask"};

                                            $http.put(url, {data: data})
                                            .success(   function(res)
                                                        {
                                                            if(res.status)
                                                                $scope.projectTasks = res.data;
                                                            else
                                                            {
                                                                $scope.showNoTask = true;
                                                                $scope.noTask = "No Task. Create one by clicking the add icon above.";
                                                            }
                                                        });
                                            $scope.noTask = "";
                                        }
                                    });
                                }
                            };
        $scope.updateProject =  function(names, prior, stat, desc, id)
                                {
                                    if(angular.isUndefined(names) || names == null)
                                        $scope.nameErrorEdit = true;
                                    else if(angular.isUndefined(desc)|| desc == null)
                                        $scope.descErrorEdit = true;
                                    else
                                    {
                                        var data =  {
                                                        name: names,
                                                        priority: prior,
                                                        status: stat,
                                                        description: desc,
                                                        id: id,
                                                        request: "updateProject"
                                                    };
                                        $http.put(url, {data: data})
                                        .success(function(res)
                                        {
                                            if(res.status)
                                            {
                                                var data = { request : "getProjects"};

                                                $http.put(url, {data: data})
                                                .success(   function(res)
                                                            {
                                                                if(res.status)
                                                                    $scope.projects = res.data;
                                                                else
                                                                {
                                                                    $scope.showNoProject = true;
                                                                    $scope.noProject = "No Project. Create one by clicking the add icon above.";
                                                                }
                                                            });
                                                $scope.showEditProject = "";
                                            }
                                        });
                                    }
                                };
        $scope.updateTask =     function(names, prior, stat, desc, id)
                                {
                                    if(angular.isUndefined(names) || names == null)
                                        $scope.nameErrorEdit = true;
                                    else if(angular.isUndefined(desc)|| desc == null)
                                        $scope.descErrorEdit = true;
                                    else
                                    {
                                        var data =  {
                                                        name:           names,
                                                        priority:       prior,
                                                        status:         stat,
                                                        description:    desc,
                                                        id:             id,
                                                        request:        "updateTask"
                                                    };
                                        $http.put(url, {data: data})
                                        .success(function(res)
                                        {
                                            if(res.status)
                                            {
                                                var data = { request : "getProjects"};

                                                $http.put(url, {data: data})
                                                .success(   function(res)
                                                            {
                                                                if(res.status)
                                                                    $scope.projects = res.data;
                                                                else
                                                                {
                                                                    $scope.showNoProject = true;
                                                                    $scope.noProject = "No Project. Create one by clicking the add icon above.";
                                                                }
                                                            });
                                                //get tasks
                                                var data = { request : "getTasks"};

                                                $http.put(url, {data: data})
                                                .success(   function(res)
                                                            {
                                                                if(res.status)
                                                                    $scope.tasks = res.data;
                                                                else
                                                                {
                                                                    $scope.showNoTask = true;
                                                                    $scope.noTask = "No Task. Create one by clicking the add icon above.";
                                                                }
                                                            });

                                                //get project tasks
                                                var data = { request : "getProjectTask"};

                                                $http.put(url, {data: data})
                                                .success(   function(res)
                                                            {
                                                                if(res.status)
                                                                    $scope.projectTasks = res.data;
                                                                if(res.message !== "")
                                                                {
                                                                    $scope.showProjectTask = true;
                                                                    $scope.projectTasks = "";
                                                                }
                                                            });
                                                $scope.showEditTask = false;
                                            }
                                        });
                                    }
                                };
        $scope.deleteProject =  function(id)
                                {
                                    $scope.showDeleteProject = false;
                                    var data = {request : "deleteProject", id: id};

                                    $http.put(url, { data : data});
                                    /*$scope.getProjects();*/
                                     //get projects
                                    var data = { request : "getProjects" };

                                    $http.put(url, {data: data})
                                    .success(   function(res)
                                                {
                                                    if(res.status)
                                                        $scope.projects = res.data;
                                                    if(res.message !== "")
                                                    {
                                                        $scope.showProjects = true;
                                                        $scope.showNoProject = true;
                                                        $scope.projects = "";
                                                    }
                                                });
                                };
        $scope.deleteTask   =   function(id)
                                {
                                    $scope.showDeleteProject = false;

                                    var data = { request: "deleteTask", id : id};

                                    $http.put(url, { data : data});
                                    
                                    //get tasks
                                    var data = { request : "getTasks"};

                                    $http.put(url, {data: data})
                                    .success(   function(res)
                                                {
                                                    if(res.status)
                                                    {
                                                        $scope.tasks = res.data;
                                                    }
                                                    //to check if no more task
                                                    if(res.message !== "")
                                                    {
                                                        $scope.showTasks = true;
                                                        $scope.showNoTask = true;
                                                        $scope.tasks = "";
                                                    }
                                                });
                                };
        $scope.deleteProjectTask   =    function(id)
                                        {
                                            $scope.showDeleteProject = false;
                                            var data = { request: "deleteTask", id : id};

                                            $http.put(url, { data : data});
                                            
                                            //get project tasks
                                            var data = { request : "getProjectTask"};

                                            $http.put(url, {data: data})
                                            .success(   function(res)
                                                        {
                                                            if(res.status)
                                                                $scope.projectTasks = res.data;
                                                            if(res.message !== "")
                                                            {
                                                                $scope.showProjectTask = true;
                                                                $scope.projectTasks = "";
                                                            }
                                                        });
                                        };
        $scope.closeMessage =   function()
                                {
                                    $scope.nameError        = false;
                                    $scope.priorError       = false;
                                    $scope.statError        = false;
                                    $scope.descError        = false;
                                    $scope.typesError       = false;
                                    $scope.nameErrorEdit    = false;
                                    $scope.descErrorEdit    = false;
                                    $scope.typesErrorEdit   = false;
                                };
        $scope.filterProject =  function()
                                {
                                    $scope.showProjects = true;
                                }
    }
    function errorContainer()
    {
      return    {
                    restrict: 'E',
                    scope: {
                      show: '='
                    },
                    replace: true, // Replace with the template below
                    transclude: true, // we want to insert custom content inside the directive
                    template: "<div ng-show='show'>" + 
                                    "<div class='ng-modal-overlay' ng-click='hideModal()'></div>" + 
                                    "<div class='ng-modal-dialog' ng-style='dialogStyle'>" +
                                    "<div ng-transclude></div>" +
                                    "</div>" +
                                "</div>",
                    link: function(scope, element, attrs) {
                      scope.dialogStyle = {};
                      if (attrs.width)
                        scope.dialogStyle.width = attrs.width;
                      if (attrs.height)
                        scope.dialogStyle.height = attrs.height;
                      scope.hideModal = function() {
                        scope.show = false;
                      };
                    }
                };  
    }
    function errorContainerHome()
    {
       return    {
                    restrict: 'E',
                    scope: {
                      show: '='
                    },
                    replace: true, // Replace with the template below
                    transclude: true, // we want to insert custom content inside the directive
                    template: "<div ng-show='show'>" + 
                                    "<div class='ng-modal-overlay' ng-click='hideModal()'></div>" + 
                                    "<div class='error-modal-dialog' ng-style='dialogStyle'>" +
                                    "<div ng-transclude></div>" +
                                    "</div>" +
                                "</div>",
                    link: function(scope, element, attrs) {
                      scope.dialogStyle = {};
                      if (attrs.width)
                        scope.dialogStyle.width = attrs.width;
                      if (attrs.height)
                        scope.dialogStyle.height = attrs.height;
                      scope.hideModal = function() {
                        scope.show = false;
                      };
                    }
                };  
    }
    function notifContainer()
    {
        return    {
                    restrict: 'E',
                    scope: {
                      show: '='
                    },
                    replace: true, // Replace with the template below
                    transclude: true, // we want to insert custom content inside the directive
                    template: "<div ng-show='show'>" + 
                                   /* "<div class='notif-overlay' ng-click='hideModal()'></div>" + */
                                    "<div class='notif-dialog' ng-style='dialogStyle'>" +
                                        "<div class='ng-modal-dialog-content' ng-transclude></div>" +
                                    "</div>" +
                                "</div>",
                    link: function(scope, element, attrs) {
                      scope.dialogStyle = {};
                      if (attrs.width)
                        scope.dialogStyle.width = attrs.width;
                      if (attrs.height)
                        scope.dialogStyle.height = attrs.height;
                      scope.hideModal = function() {
                        scope.show = false;
                      };
                    }
                };
    }
    function container()
    {
        return    {
                    restrict: 'E',
                    scope: {
                      show: '='
                    },
                    replace: true, // Replace with the template below
                    transclude: true, // we want to insert custom content inside the directive
                    template:   "<div ng-show='show'>" + 
                                    "<div class='container-dialog' ng-style='dialogStyle'>" +
                                        "<div ng-transclude></div>" +
                                    "</div>" +
                                "</div>",
                    link: function(scope, element, attrs) 
                    {
                      scope.dialogStyle = {};
                      if (attrs.width)
                        scope.dialogStyle.width = attrs.width;
                      if (attrs.height)
                        scope.dialogStyle.height = attrs.height;
                      scope.hideModal = function() 
                      {
                        scope.show = false;
                      };
                    }
                };
    }
    function signUpContainer()
    {
       return   {
                    restrict: 'E',
                    scope: {
                      show: '='
                    },
                    replace: true, // Replace with the template below
                    transclude: true, // we want to insert custom content inside the directive
                    template: "<div ng-show='show'>" +
                                    "<div class='signupmodal' ng-style='dialogStyle'>" +
                                        "<div ng-transclude></div>" +
                                    "</div>" +
                                "</div>",
                    link: function(scope, element, attrs) {
                      scope.dialogStyle = {};
                      if (attrs.width)
                        scope.dialogStyle.width = attrs.width;
                      if (attrs.height)
                        scope.dialogStyle.height = attrs.height;
                      scope.hideModal = function() {
                        scope.show = false;
                      };
                    }
                }; 
    }
})(window, window.angular);