<!doctype html>
<html ng-app = "Home_Page">
	<head>
		<title>Home</title>
		<link rel = "stylesheet" type = "text/css" href = "/Quick/css/home.css">
		<link rel = "stylesheet" type = "text/css" href = "/Quick/css/modal.css">
		<script src="/Quick/js/angular.min.js"></script>
	    <script src="/Quick/js/main.js"></script>
	</head>
	<body class = "background">
		<div ng-controller = "HomeCtrl">
			<section>
				<div class = "header">
					<div class = "header-container">
						<div class = "quick-icon"></div>
						<div ng-repeat = "user in userInformation">
							<div class = "username-container">{{ user.firstname }}</div>
						</div>
						<div class = "logout-icon" ng-click = "logout()"></div>
						<!-- <div class = "notif-icon" ng-click = "showNotif = !showNotif">
							<br><center>{{ unreadNotifs }}</center>
						</div> -->
						<div class = "add-icon" ng-click = "showAddModal = !showAddModal"></div>
						<notif-container show = "showNotif" width = "300px" height = "300px">
							<section>
				 				<div ng-repeat = "notification in notifications">
				 					
				 				</div>
				 			</section>
						</notif-container>
						<container class = "add-modal-container" show = "showAddModal" width = "470px" height = "400px">
							<section>
								<div class = "close-button" ng-click = "closeModal()"></div>
							</section><br>
							<section>
								<div><input type = "text" ng-model = "names" placeholder  = "Project/Task name here...." maxlength = "50" required></div>
							</section>
							<section>
								<div class = "section-4">
									<div class = "section-4-priority">
										<select ng-model = "prior" ng-options = "p.id as p.name for p in priorityTypes" required>
											<option value = "" disabled>Select Priority</option>
										</select>
									</div>
									<div class = "section-4-status">
										<select ng-model = "stat" ng-options = "s.id as s.name for s in statusTypesAdd" required>
											<option value = "" disabled>Select Status</option>
										</select>
									</div>
								</div>
							</section><br><br>
							<section>
								<div><textarea rows = "6" cols = "20" ng-model = "desc" placeholder = "Write description here...."></textarea></div>
							</section>
							<section>
								<div class = "section-4">
									<div class = "section-4-priority">
										<select ng-model = "types" required>
											<option value = "" disabled>Select Type</option>
											<option>Project</option>
											<option>Task</option>
										</select>
									</div>
									<div ng-click = "add(names, prior, stat, desc, types)"><button class = "save-button"></button></div>
								</div>
							</section>
						</container>
					</div>
				</div>
			</section><br>
			<section class = "padding-right">
				<input class = "filter-input" type = "text" ng-model = "search" placeholder = "Show Projects/Tasks before search">
			</section>
			<section>
			<div class = "container">
				<div class = "container-left">
					<section class =  "project-section-label" ng-click = "getProjects(); search = null; showProjects = !showProjects; showTasks = false; showRight = true">My Projects</section><br>
					<section class =  "project-section-label" ng-click = "getTasks(); search = null; showTasks = !showTasks; showProjects = false; showRight = true">My Tasks</section>
				</div>
				<!-- show Projects -->
				<div class = "container-right" ng-class = "{ 'hidden' : !showProjects }"  ng-init = "count = 0">
					<section  class = "name-label accordion"  ng-show = "showNoProject">{{ noProject }}</section>
					<div class = "accordion" ng-repeat = "project in projects | filter: search">
						<div class = "padding section">
							<div class = "delete" ng-click = "showDeleteProject = !showDeleteProject; showEditProject = false; showAddTask = false; showProjectTask = false;showEditTask = false"></div>
							<div class = "view" ng-click = "showEditProject = !showEditProject; showAddTask = false; showProjectTask = false;showEditTask = false; showDeleteProject = false"></div>
							<div class = "add-task" ng-click = "showAddTask = !showAddTask; showProjectTask = false; showEditTask = false; showEditProject = false; showDeleteProject = false" ></div>
							<div class = "name-label" ng-click = "showProjectTask = !showProjectTask; showAddTask = false; showEditTask = false; showEditProject = false;">{{ (projectTasks|filter:{projectId : project.id}).length }} {{project.name}}</div>
						</div>
						<!-- show add task in a project here -->
						<div class = "add-task-container" ng-class = "{'hidden' : !showAddTask}">
							<div class = "add-task-container-left">
								<div>
									<input type = "text" ng-model = "name" maxlength = "50" maxlength = "50" placeholder  = "Task name here...." required>
								</div>
								<div>
									<textarea rows = "6" cols = "20" ng-model = "desc" placeholder = "Write description here...."></textarea>
								</div>
							</div>
							<div class = "add-task-container-right">
								<div>
									<select ng-model = "prior" ng-options = "p.id as p.name for p in priorityTypes" required>
										<option value = "" disabled>Select Priority</option>
									</select>
								</div>
								<div>
									<select ng-model = "stat" ng-options = "s.id as s.name for s in statusTypesAdd" required>
										<option value = "" disabled>Select Status</option>
									</select>
								</div>
								<div class = "dummy"></div>
								<div class = "dummy"></div>
								<div ng-click = "addTask(name, prior, stat, desc, 'Task', project.id)">
									<button class = "save-button-1">Save</button>
								</div>
							</div>
						</div>
						<!-- end of show add task in a project here -->

						<!-- show edit project here -->
						<div class = "add-task-container" ng-class = "{'hidden' : !showEditProject}">
							<div class = "add-task-container-left">
								<div>
									<input type = "text" ng-model = "name" placeholder  = "{{ project.name }}" maxlength = "50" required>
								</div>
								<div>
									<textarea rows = "6" cols = "20" ng-model = "project.description" placeholder = "{{ project.description}}"></textarea>
								</div>
							</div>
							<div class = "add-task-container-right">
								<div>
									<select ng-model = "project.priority" ng-options = "p.id as p.name for p in priorityTypes" required>
										<option value = "" disabled>Select Priority</option>
									</select>
								</div>
								<div>
									<select ng-model = "project.status" ng-options = "s.id as s.name for s in statusTypes" required>
										<option value = "" disabled>Select Status</option>
									</select>
								</div>
								<div class = "dummy"></div>
								<div class = "dummy"></div>
								<div ng-click = "updateProject(name, project.priority, project.status, project.description, project.id, showAddTask)">
									<button class = "update-button">Update</button>
								</div>
							</div>
						</div>
						<!-- end of show edit project here -->

						<!-- delete project here -->
						<div class = "delete-container" ng-class = "{'hidden' : !showDeleteProject }">
							<div class = "padding name-label">Are you sure you want to delete this project?</div>
							<div class = "padding">
								<div class = "float-left" ng-click = "deleteProject(project.id)"><button type = "submit" class = "yes-icon">Yes</button></div>
								<div class = "float-left" ng-click = "showDeleteProject = false"><button type = "submit" class = "no-icon">No</button></div>
							</div>
						</div>
						<!-- end of delete project here -->

						<!-- show project's task/s -->
						<div ng-repeat = "task in projectTasks | filter: {projectId : project.id} | filter: search1">
							<div class = "padding" ng-show = "showProjectTask"><!-- ng-class = "{ 'hidden' : !showProjectTask}"> -->	<div class = "padding">
								<div class = "delete" ng-click = "showDeleteProject = !showDeleteProject; showEditTask = false"></div>
								<div class = "view" ng-click = "showEditTask = !showEditTask"></div>
								<div class = "name-label"> {{ task.name }}</div>	
							</div>
							<!-- show edit project's task -->
							<div class = "add-task-container" ng-class = "{'hidden' : !showEditTask}">
								<div class = "add-task-container-left">
									<div>
										<input type = "text" ng-model = "name" placeholder  = "{{ task.name }}" maxlength = "50" required>
									</div>
									<div>
										<textarea rows = "6" cols = "20" ng-model = "task.description" placeholder = "{{task.description}}"></textarea>
									</div>
								</div>
								<div class = "add-task-container-right">
									<div>
										<select ng-model = "task.priority" ng-options = "p.id as p.name for p in priorityTypes" required>
											<option value = "" disabled>Select Priority</option>
										</select>
									</div>
									<div>
										<select ng-model = "task.status" ng-options = "s.id as s.name for s in statusTypes" required>
											<option value = "" disabled>Select Status</option>
										</select>
									</div>
									<div class = "dummy"></div>
									<div class = "dummy"></div>
									<div ng-click = "updateTask(name, task.priority, task.status, task.description, task.id)">
										<button class = "update-button">Update</button>
									</div>
								</div>
							</div>
							<!-- end of show edit project's task -->

							<!-- delete task here -->
							<div class = "delete-container" ng-class = "{'hidden' : !showDeleteProject }">
								<div class = "padding name-label">Are you sure you want to delete this task under this project?</div>
								<div class = "padding">
									<div class = "float-left" ng-click = "deleteProjectTask(task.id)"><button type = "submit" class = "yes-icon">Yes</button></div>
									<div class = "float-left" ng-click = "showDeleteProject = false">
										<button type = "submit" class = "no-icon">No</button>
									</div>
								</div>
							</div>
							<!-- end of delete task here -->
							</div>
						</div>
						<!-- end of show project's task/s -->
					</div>
				</div>

				<!-- show tasks -->
				<div class = "container-right" ng-class = "{ 'hidden' : !showTasks }">
					<section  class = "name-label accordion" ng-show = "showNoTask">{{ noTask }}</section>
					<div class = "accordion" ng-repeat = "task in tasks | filter: {projectId : 0} | filter: search">
						<!-- <section class = "padding section"> -->
						<div class = "padding">
							<div class = "delete" ng-click = "showDeleteProject = !showDeleteProject; showEditTask = false"></div>
							<div class = "view" ng-click = "showEditTask = !showEditTask"></div>
							<div class = "name-label"> {{ task.name }}</div>
						</div>
						<!-- </section> -->
						<!-- show edit task -->
						<div class = "add-task-container" ng-class = "{'hidden' : !showEditTask}">
							<div class = "add-task-container-left">
								<div>
									<input type = "text" ng-model = "name" placeholder  = "{{ task.name }}" maxlength = "50" required>
								</div>
								<div>
									<textarea rows = "6" cols = "20" ng-model = "task.description" placeholder = "{{task.description}}"></textarea>
								</div>
							</div>
							<div class = "add-task-container-right">
								<div>
									<select ng-model = "task.priority" ng-options = "p.id as p.name for p in priorityTypes" required>
										<option value = "" disabled>Select Priority</option>
									</select>
								</div>
								<div>
									<select ng-model = "task.status" ng-options = "s.id as s.name for s in statusTypes" required>
										<option value = "" disabled>Select Status</option>
									</select>
								</div>
								<div class = "dummy"></div>
								<div class = "dummy"></div>
								<div ng-click = "updateTask(name, task.priority, task.status, task.description, task.id)">
									<button class = "update-button">Update</button>
								</div>
							</div>
						</div>
						<!-- end of show edit task -->

						<!-- delete task -->
						<div class = "delete-container" ng-class = "{'hidden' : !showDeleteProject }">
							<div class = "padding name-label">Are you sure you want to delete this task?</div>
							<div class = "padding">
								<div class = "float-left" ng-click = "deleteTask(task.id)"><button type = "submit" class = "yes-icon">Yes</button></div>
								<div class = "float-left" ng-click = "showDeleteProject = false"><button type = "submit" class = "no-icon">No</button></div>
							</div>
						</div>
						<!-- end of delete task -->
					</div>
				</div>
			</div>
			</section>

			<!-- error if name is empty in saving-->
			<error-container-home show = 'nameError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Saving error!</b><br></center>
                        <i>Project/Task name</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>

            <!-- error if prior is empty in saving -->
			<error-container-home show = 'priorError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Saving error!</b><br></center>
                        <i>Priority</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>

            <!-- error if stat is empty in saving-->
			<error-container-home show = 'statError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Saving error!</b><br></center>
                        <i>Status</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>

            <!-- error if desc is empty in saving-->
			<error-container-home show = 'descError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Saving error!</b><br></center>
                        <i>Description</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>

            <!-- error if type is empty in saving-->
			<error-container-home show = 'typesError' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Saving error!</b><br></center>
                        <i>Type</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>

            <!-- error if name is empty in updating-->
			<error-container-home show = 'nameErrorEdit' width = "350px">
                <div class = "error-body">
                    <div class = "error-header">
                        <div class = "error-icon"></div>
                    </div>
                    <div class = "error-message">
                        <center><b>Updating error!</b><br></center>
                        <i>Project/Task name</i> is required.<br>
                        <div ng-click = "closeMessage()"><button class = "ok-button">OK</div>
                    </div>
                </div>
            </error-container-home>
		</div>
	</body>
</html>