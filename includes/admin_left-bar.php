<?php 

global $objFunctions; 

$currentPageUrl = $objFunctions->getCurrentUrl();



if($currentPageUrl== ISP::AdminUrl('dashboard/admin_dashboard/')) 

 $dashCurrent = 'current';

 

if($currentPageUrl== ISP::AdminUrl('service/manage-locations/'))

{ 

  $locationCurrent = 'current'; 

  $manageLocation = 'active';

}

if($currentPageUrl== ISP::AdminUrl('service/add-location/')) 

{

 $locationCurrent = 'current';

 $manageLocation = 'active';

} 

if($currentPageUrl== ISP::AdminUrl('property/add-property/'))

{ 

 $locationCurrent = 'current';

 $manageProp = 'active';

} 

if($currentPageUrl== ISP::AdminUrl('property/manage-properties/'))

{ 

 $locationCurrent = 'current';

 $manageProp = 'active';

}



if($currentPageUrl== ISP::AdminUrl('property/completed-properties/'))

{ 

 $locationCurrent = 'current';

 $completeProp = 'active';

}



if($currentPageUrl== ISP::AdminUrl('property/map-builder/'))

{ 

 $locationCurrent = 'current';

 $manageMap = 'active';

}







if($currentPageUrl== ISP::AdminUrl('reports/add-new-form/')){  

 $formCurrent ='current';   

 $manageForm='active';

}
if($currentPageUrl== ISP::AdminUrl('reports/listing/'))  {

  $formCurrent ='current';

	$FormListingOpen='open';
  $manageFormListing='active';

}
if($currentPageUrl== ISP::AdminUrl('reports/reportsmanager/'))  {

  $formCurrent ='current';

	$FormListingOpen='open';
  $manageFormListing='active';

}
if($currentPageUrl== ISP::AdminUrl('reports/export-reports/'))  {  $formCurrent ='current';  $manageFormListing = 'active';}

if($currentPageUrl== ISP::AdminUrl('setting/general-settings/'))
{

 $confCurrent ='current';

 $system='active';

} 





if($currentPageUrl== ISP::AdminUrl('setting/manage-plugins/'))
{

 $confCurrent ='current';

 $plugin='active';

} 

if($currentPageUrl== ISP::AdminUrl('media/list/'))
{

 $confCurrent ='current';

 $media='active';

}

 

 



if($currentPageUrl== ISP::AdminUrl('staff/users-listing/'))

{  

   $confCurrent ='current';

   $staffOpen='open';

   $staff='active';

}



if($currentPageUrl== ISP::AdminUrl('staff/add-staff/'))  

{  

   $confCurrent ='current';

   $staffOpen='open';

   $staff='active';

}

 

if($currentPageUrl== ISP::AdminUrl('staff/import/'))  

 {  

    $confCurrent ='current';

    $staffOpen='open';

    $staff='active';

 }

 

if($currentPageUrl== ISP::AdminUrl('staff/staff_ivr_log/'))  

 {  

    $confCurrent ='current';

    $staffOpen='open'; 

    $staff='active';

 }
 if($currentPageUrl== ISP::AdminUrl('reports/report_ivr_log/') || !empty($_GET['staffid']))  

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }
 
 if($currentPageUrl== ISP::AdminUrl('reports/driver_report/') || !empty($_GET['date_from']))  

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }
 
 if($currentPageUrl== ISP::AdminUrl('reports/driver_report_log/') || !empty($_GET['driver_id']) )

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }

 if($currentPageUrl== ISP::AdminUrl('reports/property_report/') )  

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }
 if($currentPageUrl== ISP::AdminUrl('reports/season_reset/') )  

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }
 
 if($currentPageUrl== ISP::AdminUrl('reports/session_season_reset/') )  

 {  

    $formCurrent ='current';
	
	$FormListingOpen ='open';

    $manageFormListing='active';

 }

 if($currentPageUrl== ISP::AdminUrl('staff/user-permissions/'))  

  {  

 $confCurrent ='current';

 $staffOpen='open';

 $staffPerm='active';

 }

 

 if($currentPageUrl== ISP::AdminUrl('pages/technical-enquiry/'))

 {  

 $aboutCurrent ='current'; 

 $technical='active';

 }

 if($currentPageUrl== ISP::AdminUrl('pages/show-page/id/1'))  

 {

   $aboutCurrent ='current';  

   $about='active';

 }

 if($currentPageUrl== ISP::AdminUrl('pages/show-page/id/2'))  

 {

    $aboutCurrent ='current';

    $upgrade='active';

 } 

 

 if($currentPageUrl== ISP::AdminUrl('pages/show-page/id/3'))  

 {

    $aboutCurrent ='current';

    $gps='active';

 } 

 

 if($currentPageUrl== ISP::AdminUrl('pages/show-page/id/4'))  

 {

    $aboutCurrent ='current';

    $customermgm='active';

 }    

?>



		<div id="sidebar" class="sidebar-fixed">

			<div id="sidebar-content">

					<!--=== Navigation ===-->

				<ul id="nav">

					<li class="<?php echo $dashCurrent;?>">

						<a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">

							<i class="icon-dashboard"></i>

							Dashboard

						</a>

					</li>

          

<?php

		   if($objFunctions->checkPermission('Add & Edit Service Section', 'service') || $objFunctions->checkPermission('View Service Categories', 'service') || $objFunctions->checkPermission('Add & Edit Property', 'property') || $objFunctions->checkPermission('View Properties', 'property'))

		   {

?>          

					<li class="<?php echo $locationCurrent;?>">

						<a href="javascript:void(0);">

							<i class="icon-desktop"></i>

							Manage Jobs

						</a>

						<ul class="sub-menu">

    <?php

		   if($objFunctions->checkPermission('Add & Edit Service Section', 'service') || $objFunctions->checkPermission('View Service Categories', 'service'))

		   {

		 ?>

							<li>

								<a href="<?php echo ISP :: AdminUrl('service/manage-locations/');?>" class="<?php echo $manageLocation;?>">

								<i class="icon-angle-right"></i>

								Manage Locations

								</a>

							</li>

		<?php } ?>

   

   <?php

		   if($objFunctions->checkPermission('Add & Edit Property', 'property') || $objFunctions->checkPermission('View Properties', 'property'))

		   {

		 ?> 				

							<li>

								<a href="<?php echo ISP :: AdminUrl('property/manage-properties/');?>" class="<?php echo $manageProp;?>">

								<i class="icon-angle-right"></i>

								Manage Properties

								</a>

							</li>

              

              <li>

								<a href="<?php echo ISP :: AdminUrl('property/completed-properties/');?>" class="<?php echo $completeProp;?>">

								<i class="icon-angle-right"></i>

								Completed Jobs

								</a>

							</li>

              

              

              <li>

								<a href="<?php echo ISP :: AdminUrl('property/map-builder/');?>" class="<?php echo $manageMap;?>">

								<i class="icon-angle-right"></i>

								Map Builder

								</a>

							</li>

              

              

	<?php } ?>					

						</ul>

					</li>

 <?php } ?>





<?php

		if($objFunctions->checkPermission('View Reports', 'reports') || $objFunctions->checkPermission('Add & Edit Report', 'reports'))

		   {

	?>          

					<li class="<?php echo $formCurrent;?>">

						<a href="javascript:void(0);">

							<i class="icon-edit"></i>

							Reporting

						</a>

						<ul class="sub-menu">

	<?php

		if($objFunctions->checkPermission('View Reports', 'reports'))

		   {

	?>            

							<li>
                            	<a href="<?php echo ISP :: AdminUrl('reports/reportsmanager/');?>" class="<?php echo $manageFormListing;?>">
                                <i class="icon-angle-right"></i>
                                Reports Manager 
                                </a>
                            </li>
                            <!--<li>
                            	<a href="<?php echo ISP :: AdminUrl('reports/listing/');?>" class="<?php echo $manageFormListing;?>">
                                <i class="icon-angle-right"></i>
                                Reports Manager 
                                </a>
                            </li>-->

								<!--<a href="javascript:void(0);">

                                    <i class="icon-edit"></i>
    
                                    	Reports Manager
    
                                    </a>-->
                                    
                                <!--<ul class="sub-menu" <?php if($FormListingOpen == 'open') echo 'style="display:block;"';?>>
                                	<li>
                                        <a href="<?php echo ISP :: AdminUrl('reports/listing/');?>"  class="<?php echo $manageAllListing;?>">
                                        <i class="icon-angle-right"></i>
                                        Reports Manager
                                 		</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo ISP :: AdminUrl('reports/listing/');?>"  class="<?php echo $manageFormListing;?>">
                                        <i class="icon-angle-right"></i>
                                        Custom Reports
                                 		</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo ISP :: AdminUrl('reports/report_ivr_log/');?>" class="<?php echo $manageIvrListing;?>">
                                        <i class="icon-angle-right"></i>
                                        IVR Log
                                 		</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo ISP :: AdminUrl('reports/driver_report/');?>" class="<?php echo $managedriverListing;?>">
                                        <i class="icon-angle-right"></i>
                                        Driver Reports
                                 		</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo ISP :: AdminUrl('reports/property_report/');?>" class="<?php echo $managepropertyListing;?>">
                                        <i class="icon-angle-right"></i>
                                        Properties Reports
                                 		</a>
                                    </li>
                                </ul>-->

							<!--</li>                                                        <li>								<a href="<?php echo ISP :: AdminUrl('reports/export-reports/');?>" class="<?php echo $reportsExports;?>">								<i class="icon-angle-right"></i>								Export Reports								</a>							</li>-->

	<?php 

		   }

		if($objFunctions->checkPermission('Add & Edit Report', 'reports'))

		   {

	?>              

							<li>

								<a href="<?php echo ISP :: AdminUrl('reports/add-new-form/');?>" class="<?php echo $manageForm;?>">

								<i class="icon-angle-right"></i>

								Reporting Editor

								</a>

							</li>

	<?php } ?>						

						</ul>


 <?php } ?>       



<?php

if($objFunctions->checkPermission('Modify Settings', 'configuration') || $objFunctions->checkPermission('Add & Edit Staff User', 'staff') || $objFunctions->checkPermission('Assign Permission', 'staff') || $objFunctions->checkPermission('View Staff', 'staff') || $objFunctions->checkPermission('Import Staff', 'staff') || $objFunctions->checkPermission('Add & Edit Staff User', 'staff') || $objFunctions->checkPermission('View Staff', 'staff') || $objFunctions->checkPermission('Import Staff', 'staff') || $objFunctions->checkPermission('Assign Permission', 'staff') || $objFunctions->checkPermission('Manage Plugins', 'configuration'))

  {  

?>

					<li class="<?php echo $confCurrent;?>">

						<a href="javascript:void(0);">

							<i class="icon-table"></i>

							Configuration

						</a>

						<ul class="sub-menu">

    <?php

    if($objFunctions->checkPermission('Modify Settings', 'configuration'))

		   {  

		 ?>  

							<li class="<?php echo $system;?>">

								<a href="<?php echo ISP :: AdminUrl('setting/general-settings/');?>">

								<i class="icon-angle-right"></i>

								System Settings

								</a>

							</li>

<?php } 

if($objFunctions->checkPermission('Add & Edit Staff User', 'staff') || $objFunctions->checkPermission('Assign Permission', 'staff') || $objFunctions->checkPermission('View Staff', 'staff') || $objFunctions->checkPermission('Import Staff', 'staff'))

		   {

		?>

							<li class="<?php echo $staffOpen;?>">

								<a href="<?php echo ISP :: AdminUrl('staff/add-staff/');?>">

								<i class="icon-cogs"></i>

								Staff Management

								</a>

                

                <ul class="sub-menu" <?php if($staffOpen<>'') echo 'style="display:block;"';?>>

        <?php

          if($objFunctions->checkPermission('Add & Edit Staff User', 'staff') || $objFunctions->checkPermission('View Staff', 'staff') || $objFunctions->checkPermission('Import Staff', 'staff'))

		       {

       ?>   

									<li class="open-default <?php echo $staff;?>">

										<a href="<?php echo ISP :: AdminUrl('staff/users-listing/');?>">

											<i class="icon-user"></i>

											Manage Users

											<span class="arrow"></span>

										</a>

                  </li>

         <?php

              }

              if($objFunctions->checkPermission('Assign Permission', 'staff'))

		            {

              ?>         

                  <li class="open-default <?php echo $staffPerm;?>">

										<a href="<?php echo ISP :: AdminUrl('staff/user-permissions/');?>">

											<i class="icon-user"></i>

											Staff Permissions

											<span class="arrow"></span>

										</a>

                  </li> 

           <?php

                }

          ?>         

								</ul>	

						</li>

	<?php

		   }

?>

 <?php		 

        if($objFunctions->checkPermission('Manage Plugins', 'configuration'))

        {  

?>             

                <li class="<?php echo $confCurrent;?>">

                <a href="<?php echo ISP :: AdminUrl('setting/manage-plugins/');?>" class="<?php echo $plugin;?>">

                <i class="icon-angle-right"></i>

                Manage Plugins

                </a>
                </li>
                <li class="<?php echo $confCurrent;?>">
                    <a href="<?php echo ISP :: AdminUrl('media/list/');?>" class="<?php echo $media;?>">
                    <i class="icon-angle-right"></i>
                    Media Manager
                    </a>
                </li>


			<?php } ?>

          </ul>

      </li>

<?php } ?>              

              

              

<?php		 

  if($objFunctions->checkPermission('Technical Support', 'configuration') || $objFunctions->checkPermission('View License Information', 'configuration') || $objFunctions->checkPermission('Upgrades', 'configuration'))

     {  

?>              

              <li class="<?php echo $aboutCurrent;?>">

                <a href="javascript:void(0);"><i class="icon-folder-open-alt"></i>About & Extras</a> 

                  <ul class="sub-menu">  

                  <?php		 

                  if($objFunctions->checkPermission('Technical Support', 'configuration'))

                  {  

                  ?>	

                    <li  class="<?php echo $technical;?>"><a href="<?php echo ISP :: AdminUrl('pages/technical-enquiry/');?>"><i class="icon-angle-right"></i>Technical Support</a></li>

                    <li  class="<?php echo $gps;?>"><a href="<?php echo ISP :: AdminUrl('pages/show-page/id/3');?>"><i class="icon-angle-right"></i>GPS Tracking</a></li>

                    <li  class="<?php echo $customermgm;?>"><a href="<?php echo ISP :: AdminUrl('pages/show-page/id/4');?>"><i class="icon-angle-right"></i>Customer Management </a></li>

                  <?php

                  }

                  if($objFunctions->checkPermission('View License Information', 'configuration'))

                  {  

                  ?>	

                    <li class="<?php echo $about;?>"><a href="<?php echo ISP :: AdminUrl('pages/show-page/id/1');?>"><i class="icon-angle-right"></i>About</a></li>

                  <?php

                  }

                  if($objFunctions->checkPermission('Upgrades', 'configuration'))

                  {  

                  ?>	

                    <li class="<?php echo $upgrade;?>"><a href="<?php echo ISP :: AdminUrl('pages/show-page/id/2');?>"><i class="icon-angle-right"></i> Upgrades</a></li>

                  <?php

                  } 		  

                  ?>	

                  </ul>

              </li>   

<?php } ?>              

              <li>

            <a href="http://www.jobnotes.net" target="_blank">

             <img src="<?php echo SITE_URL;?>assets/img/jobnote_brand_icon.png" border="0">

            </a>

           </li>

						</ul>



				

   		</div>

			<div id="divider" class="resizeable"></div>

		</div>

		<!-- /Sidebar -->