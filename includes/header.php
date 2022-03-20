<nav class="navbar navbar--nopad navbar-expand-sm bg-dark navbar-dark sticky-top">
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link text-white js-tabindex" href="/donor_database/pages/orgs/orgs.php" tabindex="1">Orgs</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-white js-tabindex" href="/donor_database/pages/contacts/contacts.php" tabindex="2">Contacts</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-white js-tabindex" href="/donor_database/pages/donations/donations.php" tabindex="3">Donations</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-white js-tabindex" href="/donor_database/pages/imports/imports.php" tabindex="4">Imports</a>
		</li>
		<?php if(isset($_SESSION['ddb_user']) && $_SESSION['ddb_user'] == 'query_user')
				{echo '<li class="nav-item"><a class="nav-link text-white js-tabindex" href="/donor_database/utils/direct_query.php" tabindex="6">Query Tool</a></li>';}
		?>
	</ul>
	<ul class="navbar-nav ml-auto">
		<?php if(isset($_SESSION['ddb_user']))
			{echo '<li class="nav-item"><a class="nav-link js-tabindex" href="/donor_database/utils/login.php?logout=y" tabindex="5">Logout</a></li>';} 
		else 
			{echo '<li class="nav-item"><a class="nav-link" href="/donor_database/utils/login.php" tabindex="6">Log In</a></li>';} 
		?>
	</ul>
</nav>