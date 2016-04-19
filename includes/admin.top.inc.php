<!-- Header -->
<?php
global $objFunctions, $objDatabase;

$allSitesArray = $objDatabase->dbFetch("SELECT s.* FROM ".TBL_SITES." s inner join ".TBL_MODULES." m on s.module_id=m.id where m.status='1' order by m.id asc");
$allModulesArray = $objDatabase->dbFetch("SELECT * FROM ".TBL_MODULES." where id  not in(select module_id from ".TBL_SITES.") and id>1 and status='1'  order by id desc");
 
 if($_SESSION['preview']==1)
 {
   $userRole = $objFunctions->iFind(TBL_STAFFTYPE, "label", array("id"=>$_SESSION['role']));
   $userLoggedName = $objFunctions->iFind(TBL_STAFF, "f_name", array("id"=>$_SESSION['adminid']));
?>
<header class="header previewbar navbar navbar-fixed-top" role="banner">
		<!-- Top Navigation Bar -->
		<div class="container" style="padding-top:25px; text-align:center;">
        You are now logged in as <?php echo ucfirst($userLoggedName);?>... only allowed modules are loaded
         &nbsp;&nbsp;
       <a class="cancel_preview_button" href="<?php echo SITE_ADMINURL;?>?close_preview=1">Cancel & Return To Permissions</a>
    </div>
</header>    
<?php
 }
 else
 {
   if($_SESSION['adminid']<>'')
   {
?>
	<!-- Header -->
	<header class="header navbar navbar-fixed-top" role="banner">
		<!-- Top Navigation Bar -->
		<div class="container">

			<!-- Only visible on smartphones, menu toggle -->
			<ul class="nav navbar-nav">
				<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
			</ul>

			<!-- Logo -->
			<a class="navbar-brand" href="<?php echo ISP :: AdminUrl('dashboard/admin_dashboard/');?>">
				<img src="<?php echo $objFunctions->getCompanyLogo();?>" alt="logo" style="vertical-align:top; max-height:48px;" />
			</a>
			<!-- /logo -->

			<!-- Sidebar Toggler -->
			<a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
				<i class="icon-reorder"></i>
			</a>
			<!-- /Sidebar Toggler -->

			<!-- Top Right Menu -->
			<ul class="nav navbar-nav navbar-right">
				
       <!-- Project Switcher Button --> 
       
       <li>
					<a href="<?php echo SITE_URL;?>" target="_blank"><i class="icon-desktop"></i>&nbsp;&nbsp;View Site</a>
				</li>
        
        <li class="dropdown" id="layoutscreen">
					<a href="#" class="project-switcher-btn dropdown-toggle" onclick="$('#widget-switcher').hide(); $('#widgetscreen').removeClass('open');">           	
						<i class="icon-folder-open"></i>
						<span>Layouts</span>
					</a>
				</li>
        
        
        
       <!-- Widget Switcher Button -->  
         <li class="dropdown" id="widgetscreen">  
         	<a href="#" onclick="$('#layoutscreen').removeClass('open'); $('#project-switcher').hide(); $('#widget-switcher').slideToggle('fast'); $('#widgetscreen').toggleClass('open');" class="widget-switcher-btn dropdown-toggle">
				  	<i class="icon-folder-open"></i>
						<span>Widgets</span>
					</a>
				</li>

				<!-- User Login Dropdown -->
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!--<img alt="" src="assets/img/avatar1_small.jpg" />-->
						<i class="icon-male"></i>
						<span class="username"><?php echo $_SESSION['fname'];?></span>
						<i class="icon-caret-down small"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo ISP :: AdminUrl('staff/my-profile');?>"><i class="icon-user"></i> My Profile</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo SITE_URL;?>logout.php"><i class="icon-key"></i> Log Out</a></li>
					</ul>
				</li>
				<!-- /user login dropdown -->
			</ul>
			<!-- /Top Right Menu -->
		</div>
		<!-- /top navigation bar -->


    <!--=== Project Switcher ===-->
		<div id="project-switcher" class="container project-switcher">
			<div id="scrollbar">
				<div class="handle"></div>
			</div>

			<div id="frame">
				<ul class="project-list">
        <?php
         foreach($allSitesArray as $siteObj)
         {
        ?>
         <li class="<?php if($siteObj->site_id==$_SESSION['site_id']) echo 'current';?>">
						<a href="<?php echo ISP :: AdminUrl('dashboard/admin_dashboard/site_id/'.$siteObj->site_id);?>">
							<div class="<?php echo $siteObj->icon_class;?>"></div>
							<span class="title"><?php echo $siteObj->site_title;?></span>
						</a>
					</li>
        <?php
        }
        ?>
 				</ul>
			</div> <!-- /#frame -->
		</div> <!-- /#project-switcher -->
    
    <!--=== Widget Switcher ===-->
    <div id="widget-switcher" class="widget-switcher">
			<h6>Show Dashboard Widgets</h6>

			<div id="frame">
				<ul class="project-list">
         <li class="">
						<a href="javascript:void(0);">
            <input type="checkbox" name="widget_location" value="1" checked onclick="$('.dragwidget_location_status_details').parent().toggle();">
							<span class="title">Location Status & Details</span>
						</a>
					</li>
      <?php
      $j=0;
      foreach($allModulesArray as $moduleLoaded)
       {
         $j++;
         if(trim($moduleLoaded->id)<>'') {
      ?>
         <li class="">
						<a href="javascript:void(0);">
            <input type="checkbox" name="widget_location" value="1" checked onclick="$('.dragwidget_<?php echo $objFunctions->cleanURL($moduleLoaded->title);?>').parent().toggle();">
							<span class="title"><?php echo $moduleLoaded->title;?></span>
						</a>
					</li>
        <?php
           }
        }
        ?>
        
         
         <!--<li class="">
						<a href="javascript:void(0);">
             <input type="checkbox" name="widget_2" value="1" checked onclick="$('#drag3').toggle();">
							<span class="title">Staff</span>
						</a>
					</li>-->
          
          
          
				</ul>
			</div> <!-- /#frame -->
		</div> <!-- /#Widget-switcher -->
		
	</header> <!-- /.header -->
<?php
  }
  else
  {
?>
    <div class="logo">
    	<img src="<?php echo SITE_URL;?>assets/img/admin_logo.png" alt="logo" />
    </div>
<?php 
  }
}
?>  