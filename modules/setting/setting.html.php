<?php

/**

 * @Date: 17/02/2015

 * @Purpose: Class for Setting Module which inherits the HTML view class

*/

class HTML_SETTING

{

   private $objFunction;

   private $objDatabase;

   

   public function __construct($objfunc) //define Constructor

   {

     $this->objFunction = $objfunc;		

	   $this->objDatabase 	= new Database();

   }

   

protected function addPlugin()

{

?>

<div id="content">

	<div class="container">  

      <div class="crumbs">

      	<ul id="breadcrumbs" class="breadcrumb">

      		<li>

      			<i class="icon-home"></i>

      			<a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>

      		</li>

          <li>

      			<i class="current"></i>

      			<a href="#">Configuration</a>

      		</li>

          <li>

      			<i></i>

      			<a href="<?php echo ISP::AdminUrl('setting/manage-plugins/');?>">Manage Plugins</a>

      		</li>

          

          <li>

      			<i class="current"></i>

      			<a href="#">Add New Plugin</a>

      		</li>

      	</ul>

      </div>

      

    <div class="row">

      <div class="col-md-12">   

        <h4 class="text-left heding_6">Add New Plugin</h4>

        <div class="widget box box-vas">

          <div class="widget-content formTableBg widget-content-vls">

            <div class="form_holder">      

              <form name="frmContent" method="post" onsubmit="return validateFrm(this);" enctype="multipart/form-data">		

                <div class="form-group">			    

                  <label for="exampleInputEmail1">Upload Plugin Zip File</label>			    

                  <input type="file" name="md_plugin" class="form-control" required accept="application/zip,application/x-zip,application/x-zip-compressed"> 			

                </div> 

                <div class="clearfix"></div> 			 

                <button type="submit" name="save_new" value="Upload" class="btn btn-default section_button ">Upload</button>		

                

               </form>

             </div>

           </div>

          </div>

        </div>

     </div>           

                

                

  </div>

</div>     

<?php

}   



protected function listModules($objRs)

{

?>

<script type="text/javascript">

$(document).ready(function() {

    //$('.datatable').dataTable( {} );

} );

</script>    

   <div id="content">

			<div class="container">

      

        <div class="crumbs">

        	<ul id="breadcrumbs" class="breadcrumb">

        		<li>

        			<i class="icon-home"></i>

        			<a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>

        		</li>

            <li>

        			<i class="current"></i>

        			<a href="#">Configuration</a>

        		</li>

            <li>

        			<i class="current"></i>

        			<a href="#">Manage Plugins</a>

        		</li>

        	</ul>

        </div>  





				<!--=== Normal ===-->

<div class="row">

		<div class="col-md-12">

    <h4 class="text-left heding_6">Manage Plugins</h4>



						<div class="widget inside_widget box box-vas">

							

							<div class="widget-content widget-content-vls">

                <form method="post" name="frmListing">

                <div class="col-md-12 text-right" style="padding-bottom:10px">

                 <a href="<?php echo ISP::AdminUrl('setting/addplugin/');?>"><input type="button" class="btn btn-success btn-ms" name="btn_Publish" value="Add New Plugin"/></a>
                 
                 <a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>"><input type="button" class="btn btn-ms" name="btn_dashboard" value="View Dashboard"/></a>

                <!-- <input type="submit" class="btn  btn-ms" name="btn_UnPublish" value="View Dashboard"/>-->

               

                </div>

                  <table class="table table-striped table-bordered table-hover table-checkable table-responsive datatable1" id="dataTables-example1">

                    <thead class="cf">

											<tr>

                          <th align="center" data-hide="phone">Status</th>

											    <th data-class="expand">Plugin</th>

												  <th data-hide="phone">Description</th>

                      </tr>

									</thead>

									<tbody>

<?php

			

			if($objRs): // Check for the resource exists or not

			 $intI=1;

		 

			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array

			  {

			    $strStatus = ($objRow->status)?'0':'1';

				if($intI++%2==0)  // Condition for alternate rows color

				   $strCss='evenTr';

				else

				   $strCss='oddTr';

			?>

          <tr>

            <td>

            <?php if($objRow->id >1) { ?>  

            <a href="<?php echo ISP::AdminUrl('setting/updateplugin/id/'.$objRow->id.'/st/'.$strStatus);?>">

            <?php } ?>

            <i class="fa fa-toggle-<?php if($objRow->status==1) echo 'on'; else echo 'off';?>"></i>

            <?php if($objRow->id >1) { ?>  

            </a>

            <?php } ?>

            </td>

            <td align="left" width="20%">

            <b><?php echo $objRow->title;?></b>

            <br>

            <?php if($objRow->id >1) { ?> 

            <a href="<?php echo ISP::AdminUrl('setting/removeplugin/id/'.$objRow->id);?>" onclick="return confirm('Are you sure to remove this plugin permanently.\n\nOnce it is removed it can not be restored.');">Delete</a> | 

            <?php } ?>

            <a href="javascript: void(0);">Details</a> | Version <?php echo $objRow->version;?>

            

            </td>

             <td align="left"><?php echo $objRow->description;?></td>

            </tr>

          <?php

			   }

			   ?>

           </tr>

          <?php

			 else:

			       echo '<tr><td  class="errNoRecord">No Record Found!</td></tr>';

			 endif;  

			 ?>

          </tbody>

        </table>

         <input type="hidden" name="status" value="1" />

        <input type="hidden" name="task" value="modifystaff" />

             

    	  </form>

			</div>

    </div>

</div>

</div>

				<!-- /Normal -->

			</div>

			<!-- /.container -->



		</div>

<?php

}



protected function admin_Config($objRs)

{

  ?>

  <div id="content">

			<div class="container">

        <div class="crumbs">

	<ul id="breadcrumbs" class="breadcrumb">

		<li>

			<i class="icon-home"></i>

			<a href="<?php echo ISP::AdminUrl('dashboard/admin_dashboard/');?>">Dashboard</a>

		</li>

    <li>

			<i class="current"></i>

			<a href="#">Configuration</a>

		</li>

    <li>

			<i class="current"></i>

			<a href="#">System Settings</a>

		</li>



	</ul>

</div>  



      <div class="row">

      <div class="col-md-12">   

        <h4 class="text-left heding_6">System Settings</h4>

        <div class="widget box box-vas">

          <div class="widget-content formTableBg widget-content-vls">

            <div class="form_holder"> 

				<form method="post" name="frmAdd" enctype="multipart/form-data">  
<?php if( isset($_POST['save']) ) 
{
	 $sdata = $_POST['value'];
		
	 $savedata = $this->saveConfig();
	 if($savedata)
	 {
	 	echo $savedata;	 
	 }
	 $objRs  = $this->getconfig(); 	 
}

       while($objRow = $objRs->fetch_object()) // Fetch the result in the object array

		{

              ?>          

           <div class="form-group">	

              <label for="exampleInputEmail1"><?php echo $objRow->config_label;?></label>

          <?php

           if($objRow->config_key=='site_timezone')

           {

          ?>

            <select name="value[<?php echo $objRow->config_key;?>]" class="form-control">

             <?php $this->objFunction->dropdownTimeZone($objRow->config_value);?>

            </select> 

           <?php

           }

          elseif($objRow->config_key=='sidebar_welcome_message')

          {

          ?>

          <br>

          <small>Enter a customized message that will be shown below the menu on the site. This is great for contact phone numbers, urgent messages and reminders.</small>

          <br>

          <textarea  class="form-control" rows="5" name="value[<?php echo $objRow->config_key;?>]"><?php echo $objRow->config_value;?></textarea>

          <?php

          } 

           elseif($objRow->config_key=='site_logo')

           {

            ?>

            <br><small>Customize your system with your company logo. For best results, use a light colored logo if possible in .PNG format. The recommended size is 175px x 48px. (Note: .jpg, .png and .gif file formats allowed)</small>

            <br><input type="file" accept="image/*" name="logo">

            <?php if($objRow->config_value<>''){ ?>

            <br/>

            <img src="<?php echo SITE_URL;?>upload/<?php echo $objRow->config_value;?>" height="100">

            <?php

             }

           } 

           else

           {

          ?>    

              <input type="text" name="value[<?php echo $objRow->config_key;?>]" value="<?php echo $objRow->config_value;?>" class="form-control"/>		  

            <?php

            }

    



    // now you can use $options;





            ?>

            

            </div> 

<?php

		 }

            	?>  	 	    

            <button type="submit" name="save" class="btn btn-default section_button ">Save Settings </button>	

    <!--<input type="hidden" name="task" value="saveConfig" />	-->

  </form>	 	

</div>

</div> 

</div>

</div> 

</div>

</div> 

</div>

<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>

<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

	

<?php 

}

  

  

/**

       *@Purpose: HTLM view of the change password form to Admin

*/

   protected function changePassword()

   {

      if($this->objFunction->displaySessMsg()){

	 		echo $this->objFunction->displaySessMsg();

			unset($_SESSION['sessMsg']);

		}

   ?>      

<table width="100%" border="0" cellspacing="2" cellpadding="2" class="formhead" align="center">  		

  <tr>            

    <td class="middle_text" bgcolor="#dedfe4"><h1>Change Admin Password</h1></td>          

  </tr> 		

  <tr>    		 <td>			 

      <form method="post" name="frmChange"  onsubmit="return ValidateChForm(this);">			   

        <table width="95%" border="0" cellspacing="1" cellpadding="0" align="center">			     

          <Tr>				   

            <td width="120"><strong>Old Password</strong></td>				    <td>

              <input type="password" name="md_old_password" class="text_field"  maxlength="15"/></td>				 

          </Tr>				

          <Tr>				   <td><strong>New Password</strong></td>				    <td>

              <input type="password" name="md_new_password" class="text_field" maxlength="15"/></td>				 

          </Tr>	 				

          <Tr>				   <td><strong>Confirm Password</strong></td>				    <td>

              <input type="password" name="md_con_password" class="text_field"  maxlength="15"/></td>				 

          </Tr>				 

          <tr>				 <td>&nbsp;</td>				 <td>

              <input type="submit" name="save" value="Change" class="button" alt="Save" title="Save"/>&nbsp;

              <input type="reset" value="Reset" onchange="ResetJs();" class="button" alt="Reset" title="Reset"/>	  	 			   

        </table>			    			    

        <input type="hidden" name="action" value="save" />	  			 			  

      </form>			 </td>		

  </tr>   

</table>	    

<script type="text/javascript">

		function ValidateChForm(tmpVar)

		{

		   with(tmpVar)

		   {

		    

			  if(blankField(md_old_password,'Please enter old password'))

			  return false 

			  if(blankField(md_new_password,'Please enter new password'))

			   return false 

			  if(invalidLength(md_new_password,'New password should have ',6,15))

			    return false; 

			   

			  if(md_old_password.value == md_new_password.value)

			   {

				addMessage(md_new_password, "Old password and New password should not be same");

				 return false;

			   }

			 if(blankField(md_con_password,'Please enter confirm password'))

			   return false

			 

			 if(md_con_password.value != md_new_password.value)

			  {

			    addMessage(md_con_password, 'New Password and confirm Password are not same');

			     return false;

		     }  

		  }

		   return true;

		}

		</script> 		       

<?php  

   }

  

}   

?>