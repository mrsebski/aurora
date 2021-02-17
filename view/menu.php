		<header class="header_area">
			<div class="main_menu">
				<nav class="navbar navbar-expand-lg w-100">
					<div class="container">
						<div class="logo">
							<a class="navbar-brand" href="/">
								<!-- <img src="../images/logo_so.png" alt="" /> -->
								<div class="slash"> effe   </div>
								<div class="extension"> website </div>
							</a>
						</div>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
						aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
							<div class="row w-100">
								<div class="col-lg-12 pr-lg-0">
									<ul class="nav navbar-nav ml-auto justify-content-end">
										<li class="nav-item @if(Request::is(Request()->getPathInfo())) active @endif">
											<a class="nav-link" href="{{route('index')}}">Home</a>
										</li>
										<!-- Authentication Links -->
										<?php
										// Initialize the session
										session_start();
										

										// Check if the user is logged in, if not then redirect him to login page


										if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
										echo '
										<li class="nav-item ">
											<a class="nav-link" href="login.php">
												Login
											</a>
										</li>
											<li class="nav-item ">
											<a class="nav-link" href="register.php">
												Register
											</a>
										</li>
										';
										}else{
											echo '
											<li class="nav-item dropdown ">
											<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
													'.$_SESSION["username"].'
												<img class="pl-2" height="25" src="/public/icons/settings.svg" alt="...">
											</a>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
												<a class="dropdown-item" href="/articles.php">
													Twoje artykuły
												</a>
												<a class="dropdown-item" href="/addArticle.php">
													Dodaj artykuł
												</a>
												<a class="dropdown-item" href="logout.php"
													">
													Wyloguj się
												</a>
												
											</div>
										</li>
										';
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</header>
		<div class="container">
      <!-- Example row of columns -->
      <div class="row mt-5">
