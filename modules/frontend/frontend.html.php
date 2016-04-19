
<?php
/**
 * @Purpose: Class for HTML view of the Content Module
*/

class FRONTEND_HTML_CONTENT
{
   private $objFunction;
   
   public function __construct($objfunc) //define Constructor
   {
     $this->objFunction = $objfunc;		
   }

   /**
       *@Purpose: HTML View of the listing of the added content to Admin
   */
   
      protected function show_home()
   {
   ?>
     <div class="container"><!-- mid section -->
   <div class="container-fluid"> <!-- colorful line -->
					<div class="col-xs-2 purple"></div>
					<div class="col-xs-2 yellow"></div>
					<div class="col-xs-2 red"></div>
					<div class="col-xs-2 purple"></div>
					<div class="col-xs-2 yellow"></div>
					<div class="col-xs-2 red"></div>
					<div class="clearfix"></div>
			    </div>
                <div class="container"><!-- mid section -->
  	<div class="col-md-5 pull-right message top-margin-45">
				join<b> Yomi Black</b> on the <b> Radio Hit Show ( HYPE: <img src="images/hype.png" alt="image"> )</b>
				       
			</div>
            
            <div class="container text-format">

		 	 <h1 class="top-margin-110">Plug in and Discover quality audio<br>content - anytime, anywhere.</h1>
			                   
		     	 <a href="<?php echo ISP ::FrontendUrl('frontend/index/');?>" class="btn btn-default btn-img">Begin Experience with Vibeoo</a>
			    <p class="link-style"><a href="">login or sign up with social media & email.</a></p>
			</div>
             
      <div class="clearfix"></div>
            </div>
          
</div>
<?php 
   }   //end function
   
   
   protected function show_explore($objRs, $type='')  //function to show explore page
   {
   ?>
   <div class="container mid-section"><!-- mid section -->
  <div class="container-fluid">
        <div class="jumbotron header-banner">
	        <div class="pull-right cover-heading">
		        <p class="j_date">14 June 2014</p>
		        <p class="j_heading">Banky W, From Nigeria<br> with Love</p>
		        <p class="j_para">
		        	He is an old-fashioned romantic, love advocate,<br> a charity activist, and a full-time ...  <a class="btn-lg btn-style" href="#">Read More</a>
		        </p>
	        <div class="clearfix"></div>
	        </div>
	    <div class="clearfix"></div>
        </div>
    </div>   
    <div class="hub-container">
        	
                <h2 class="heading-h2">HUBS</h2>
                <div class="hub-section">
                <div class="row col-xs-offset-0 row-hub"> 
    			 <?php
				if($objRs)
				{
					
			 // Check for the resource exists or not
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {?>
			    <div class="col-md-5ths">
                        <div class="hub-item-box">
                            <img src="<?php echo ROOT_FOLDER.'upload/'.$objRow->bg_image;?>" alt="image">
                        <div style="clear:both"></div>
                        
                        </div>                   
                    </div>
       		<?php }
				}?>
         
           </div>
           </div>
           </div>
           </div>
          

<?php 
   }   //end function
   
   protected function show_index($objRs, $type='')
   { ?>
   <div class="container mid-section"><!-- mid section -->    
    
    <!--header banner start from here -->
    <div class="container-fluid">
        <div class="jumbotron header-banner">
	        <div class="pull-right cover-heading">
		        <p class="j_date">14 June 2014</p>
		        <p class="j_heading">Banky W, From Nigeria<br> with Love</p>
		        <p class="j_para">
		        	He is an old-fashioned romantic, love advocate,<br> a charity activist, and a full-time ...  <a class="btn-lg btn-style" href="#">Read More</a>
		        </p>
	        <div class="clearfix"></div>
	        </div>
	    <div class="clearfix"></div>
        </div>
    </div>

<!-- music player start from here -->
        <div class="container-fluid headerplayarea">
                <div class="col-lg-4 nopadding">
                    <div class="col-md-12 nopadding">
                      <img src="<?php echo SITE_IMAGEURL ?>header_pod.png">
                    <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-6 nopadding">
                	<img src="<?php echo SITE_IMAGEURL ?>header_playstream.png">
                    
                <div class="clearfix"></div>
                </div>

                <div class="col-md-2 right-tab">

                    <div class="span4 arrow-next"><a href="">
                        <img src="<?php echo SITE_IMAGEURL ?>arrow.png" alt="image"></a>
                    </div>
                    <div class="span8 list-next">
                        Next
                    </div>
                    <div class="span8 list-qt">
                        (4)
                    </div>

                </div>
        <div class="clearfix"></div>
        </div>
        <div class="container-fluid">
            <div class="col-xs-2 purple"></div>
            <div class="col-xs-2 yellow"></div>
            <div class="col-xs-2 red"></div>
            <div class="col-xs-2 purple"></div>
            <div class="col-xs-2 yellow"></div>
            <div class="col-xs-2 red"></div>
        <div class="clearfix"></div>
        </div>
<!-- music player end here -->
        
<!-- music RECENT EPISODES -->
    <div class="container-fluid">
    	<div class="gallery-style">
                <div class="page-header">
                   <p class="type-heading">RECENT EPISODES
                   </p>
                </div>
                <div class="row col-xs-offset-0">
                 <?php
				if($objRs)
				{
					
			 // Check for the resource exists or not
			  while($objRow = $objRs->fetch_object())  // Fetch the result in the object array
			  {?>
                    <div class="col-md-5ths">
                        <p class="epi-time">1 hour ago</p>
                        <img src="<?php echo ROOT_FOLDER.'upload/'.$objRow->album_pic;?>" alt="image">
                        <div class="epi-title"><?php echo $objRow->episode_title;?></div>
                        <div class="epi-place"><?php echo $objRow->artist_name;?></div>
                    </div>
                   <?php }
				}?>
                    
                </div><!-- row -->
         </div>
    </div>

<!-- RECENT EPISODES END HERE -->


<!-- RECENT TRENDS START FROM HERE -->
    <!--<div class="container-fluid">
        <div class="gallery-style">
            <div class="page-header">
               <p class="type-heading">TRENDING
               </p>
            </div>
            <div class="row col-xs-offset-0">
           <?php 
		//$result= $this->objFunction->iFindAllCount(TBL_EPISODE,episode_count,4); //calling function to retrive most listned episodes
		//while($row = $result->fetch_object())
		//{ 
					 ?>
				<div class="col-md-5ths">
                    <p class="epi-time">1 hour ago</p>
                    <img src="<?php //echo ROOT_FOLDER.'upload/'.$row->album_pic;?>" alt="image">
                    <div class="epi-title"><?php // echo $row->episode_title;?></div>
                    <div class="epi-place"><?php //echo $row->artist_name;?></div>
                </div>
	<?php //}
		 ?>
		   </div> 
        <div class="clearfix"></div>
        </div>
    </div>-->

<!-- RECENT TRENDS END HERE -->


<!-- button load more -->
        <div class="centered">
            <button type="button" class="btn btn-default">LOAD MORE</button>
        <div class="clearfix"></div>    
        </div>
<!-- button load more end -->

</div>

  <?php  } 
 
   
} // End of Class
?>
