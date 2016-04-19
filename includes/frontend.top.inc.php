<?php
global $objFunctions;
if($_SESSION['adminid']<>'')
   {
?>
<header class="header navbar navbar-fixed-top staff-header staff_top" role="banner">		
  <!-- Top Navigation Bar -->		
  <div class="navbar-header">	   
    <div class="col-md-12 nav-inner">      
      <div class="col-md-3 col-xs-12 logo-top centered">       
        <a class="navbar-name1" href="<?php echo SITE_URL;?>">	
          <img src="<?php echo $objFunctions->getCompanyLogo();?>" alt="logo" style="max-height:65px !important;" /></a>      
        <div class="clearfix">
        </div>      
      </div>  		
      <div class="col-md-5 col-xs-12 form-top">					
        <form class="topsearchbox" action="<?php echo SITE_URL;?>index.php" method="get">						
          <div class="input-group col-xs-12">              
            <input id="search" class="form-control" required placeholder="search" name="keyword" type="text">              
            <span>
              <button class="btn btn-default btn_search" type="submit">
                <i class="glyphicon glyphicon-search"></i>
              </button>
            </span>					  
          </div>           
          <input type="hidden" name="dir" value="property">           
          <input type="hidden" name="task" value="search">    					
        </form>      
        <div class="clearfix">
        </div>     			
      </div>             
      <div class="col-md-4 col-xs-12 log-button">       	 
        <a href="<?php echo SITE_URL;?>index.php?dir=staff&task=my-profile">         
          <span class="username">Welcome 
            <?php echo $_SESSION['fname'];?>
          </span>         </a>  			
        <span class="profile_divider"> | 
        </span>  			
        <span class="username">
          <a href="<?php echo SITE_URL;?>logout.php">Log Out</a>
        </span>        
<?php
         if($_SESSION['adminid']==1){
                ?>        
        <span class="profile_divider"> | 
        </span>  			
        <span class="username">
          <a href="<?php echo SITE_ADMINURL;?>">Go to Admin</a>
        </span>        
<?php
                } ?>        
        <div class="clearfix">
        </div>      
      </div>         			
    </div>

    <button type="button" class="navbar-toggle nopadding pull-right col-md-1 collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">		        
      <span class="sr-only">Toggle navigation
      </span>		        
      <span class="icon-bar">
      </span>		        
      <span class="icon-bar">
      </span>		        
      <span class="icon-bar">
      </span>                   
    </button>    
    <div class="clearfix">
    </div>   		
  </div>
</header>


<!-- /.header -->
<?php
}
else
{?>  
<div class="logo">    	
  <img src="<?php echo SITE_URL;?>assets/img/admin_logo.png" alt="logo" />  
</div>
<?php
}
?>