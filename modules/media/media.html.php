<?php
class HTML_MEDIA {
    private $objFunction;
    public function __construct($objfunc) {
        $this->objFunction = $objfunc;
    }
    
    protected function medialist($filearray) {
        $totalImages = count($filearray);
    ?>
    <div id="content">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-left heding_6">Media Manager</h4>
                    <div class="widget box box-vas">
                        <div class="widget-content formTableBg widget-content-vls">
                        <form method="post">
                            <input type="hidden" name="dir" value="media">
                            <input type="hidden" name="task" value="download-image">
                            <span><b>Total Images:</b> <?php echo $totalImages;?>  </span>
                            <span><b>Optimized Images:</b> <?php $glob = glob("upload/mobile/{*.jpg,*.gif,*.png,*.PNG,*.JPG,*.JPEG,*.GIF}", GLOB_BRACE);echo count($glob);?></span>
                            <span id="progress">
                            <?php
                            if ($totalImages >count($glob)) {
                                ?>
                            <input type="button" value="Optimize All" onclick="optimizeImages();">
                            <?php 
                            }
                            ?>
                            </span>
                            <span><a href="<?php echo SITE_ADMINURL;?>index.php?dir=media&task=export" target="_blank"><button>Export Optimized Images</button></a></span>
                            <span><input type="submit" value="Export Selected" name="saveselected">
                            <span><input type="submit" onclick="return confirm('Only the selected images will be removed. The properties will not be affected with this deletion but if these images are attached then property will show blank images.');" value="Remove Selected" name="rmselected">
                            <div class="form_holder mediamanager" style="padding-top:20px;">
                            <?php
                                if (!isset($_GET['page'])) {
                                    $currentPage = 1;
                                }
                                else {
                                    $currentPage = $_GET['page'];
                                }
                                $start = ($currentPage-1)*30;
                                $limit = $start+30;
                                
                                if ($limit >$totalImages) {
                                    $limit = $totalImages;
                                }
                                $totalPages = ceil($totalImages/30);
                                for($i = $start; $i< $limit; $i++) {
                                    $file = $filearray[$i];
                                ?>
                                <div style="margin: 0px 10px 10px 0px; position: relative; width: 160px; height: 220px; float:left; text-align:center; border:1px solid #ccc; overflow:hidden;">
                                    <input style="display:none;" onclick="$(this).is(':checked')?$('#img_check_<?php echo $i;?>').show(1000):$('#img_check_<?php echo $i;?>').hide(1000);" type="checkbox" id="check_<?php echo $i;?>" value="<?php echo basename($file);?>" name="imgdownload[]">
                                    <label for="check_<?php echo $i;?>" id="img_check_<?php echo $i;?>" style="display: none; position: absolute; left:0px; width:160px; height:110px;">
                                    <img src="<?php echo SITE_URL;?>upload/tmp/trans_checked.png" style="width:160px; height:110px;">
                                    </label>
                                    <label for="check_<?php echo $i;?>" style="cursor: pointer;">
                                    <img alt="Click to Select" title="Click to Select" src="<?php echo SITE_URL;?>upload/tmp/blank.gif" data-src="<?php echo SITE_URL.$file;?>" style="width:160px !important; height: 110px !important;" border="0" width="130" height="110">
                                    </label>
                                    <br>                                    
                                    <?php echo substr(basename($file),0,30);?>
                                    <br>
                                    <?php
                                    $org_size = filesize('upload/'.basename($file));
                                    $compress_size = filesize('upload/mobile/'.basename($file));
                                    ?>
                                    Actual Size: <?php echo $this->objFunction->sizeFormatter($org_size);?><br>
                                    Optimized Size: <?php echo $this->objFunction->sizeFormatter($compress_size);?><br>
                                    Reduced Size: <?php echo round(((($org_size-$compress_size)*100)/$org_size),2).' %';?>
                                </div>
                                <?php
                                }
                            ?>                            
                            </div>
                            <div class="clear"></div>
                            <div>
                                Go to Page: <select id="page" onchange="window.location='<?php echo SITE_ADMINURL;?>media/list/page/'+this.value">
                                <?php
                                for ($j=1; $j<=$totalPages; $j++) {
                                ?>
                                <option value="<?php echo $j;?>" <?php if ($j==$currentPage) echo 'selected';?>><?php echo $j;?></option>
                                <?php
                                }
                                ?>
                                </select>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script defer>
function lazyloadMedia() {
     $("div.mediamanager img").each(function() {
        $(this).attr("src", $(this).attr("data-src"));
    });
}
function sendtooptimize(index) {
   $.ajax({
        method: "POST",
        url: "<?php echo SITE_ADMINURL;?>media/optimize",
        data: { index: index}
    })
    .done(function( msg ) {
        var nexttIndex = parseInt(index)+10;
        if (nexttIndex<=<?php echo $totalImages;?>) {
            
            $("#progress").html('Optimization of images in progress '+nexttIndex+' out of <?php echo $totalImages;?>');
            sendtooptimize(nexttIndex);
        }
        else {
            $("#progress").html('');
        }
    }); 
}
function optimizeImages() {
    var imgindex=1;
    $("#progress").html('Optimization of images in progress '+imgindex+' out <?php echo $totalImages;?>');
    
    sendtooptimize(imgindex);
}
setTimeout(function(){
    lazyloadMedia();
}, 6000);
</script>
    <?php
    }
	//
protected function exportPropertyImages($objRs) {
	 ?>
	<div id="content">
		<div class="container"> 
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-left heding_6">Export Property Images</h4>
                     <form method="post" id="export_image_form">
                         <button type="button" id="select_check_all" style="">Select All/Check All</button>
                         <button type="button" id="expand_collapse_all" style="">Expand All/Collapse All</button>
                         <p style="float:right;"><input type="submit" value="Export ZIP" name="saveselected_z">
                            <input type="submit" value="Export PDF" name="saveselected_p"></p>
                         
                            <input type="hidden" name="task" value="download-property-image">
					<?php $i=1; $locarr = array();
						while ($objRow = $objRs->fetch_object()) {
							unset($imgarr);
							$imgarr = array();
							if ($objRow->user_gallery<>''){
								if( !in_array($objRow->location_id, $locarr) ) 
								{
									$location = $this->objFunction->getLocationName($objRow->location_id);
									echo '<h4><strong>'.$location.'</strong></h4>';
									$locarr[] = $objRow->location_id;
								}
							?>
<p class="accordion">
  <input type="checkbox" name="select_all" class="select_all" value="<?php echo $objRow->id; ?>"/>
<span class="toggle-title"><?php echo $objRow->job_listing; ?></span></p>
								<div class="toggle-content" id="<?php echo $objRow->id; ?>">
								<?php
								if (strpos($objRow->user_gallery, ',') !== false) {
									$images = explode(',', $objRow->user_gallery);
									foreach($images as $img){ 
										if($img != ''){
											$imgarr[] = $img;
										}
									}
								}
								else{
									$imgarr[] = $objRow->user_gallery;
								}
								$totalImages = count($imgarr);
								foreach($imgarr as $ia){
								$file = "upload/".$ia;
								?>
									<div style="margin: 0px 10px 10px 0px; position: relative;float:left; text-align:center;overflow:hidden;">
								<input class="select_individual" style="display:none;" onclick="$(this).is(':checked')?$('#img_check_<?php echo $i;?>').show(1000):$('#img_check_<?php echo $i;?>').hide(1000);" type="checkbox" id="check_<?php echo $i;?>" value="<?php echo basename($file);?>" name="<?php echo $objRow->job_listing; ?>[]">
								<label for="check_<?php echo $i;?>" id="img_check_<?php echo $i;?>" style="display: none; position: absolute; left:0px; width:160px; height:110px;">
								<img src="<?php echo SITE_URL;?>upload/tmp/trans_checked.png" style="width:160px; height:110px;">
								</label>
								<label for="check_<?php echo $i;?>" style="cursor: pointer;">
								<img class="prop_exp_img" alt="Click to Select" title="Click to Select" src="<?php echo SITE_URL.$file;?>" data-src="<?php echo SITE_URL.$file;?>" border="0" style="width:160px;height:110px;">
								</label>
							</div>
								<?php
								$i++;
							}
								?>
								</div>
							<?php 
							}
						}
					?>
                     <p style="float:right;"><input type="submit" value="Export ZIP" name="saveselected_z">
                            <input type="submit" value="Export PDF" name="saveselected_p"></p>
                     <div style="clear:both;"></div>
                    </form>
				</div>
			</div>
		</div>
	</div>
    
	<script type="text/javascript">
		//
		$(document).ready(function(e) {
			//
			$('#export_image_form').submit(function(){
				var sel_indiv;
				var sel_all;
				$('input:checkbox.select_individual').each(function (){
					var sThisVal = $(this).prop('checked');
					if( sThisVal == true )
						sel_indiv = sThisVal; 
					
				});
				$('input:checkbox.select_all').each(function (){
					var sThisVal = $(this).prop('checked');
					if( sThisVal == true )
						sel_all = sThisVal; 
					
				});
				if( sel_indiv == true || sel_all == true)
				{
					return true;
				}
				else
				{
					alert('Please select images to export.');
					return false;
				}
			});
			//
			$(".toggle-content").hide();
			$(".toggle-title").click(function() {
				//$(this).toggleClass('active').next(".toggle-content").slideToggle("normal");
				$(this).parent('p.accordion').toggleClass('active').next(".toggle-content").slideToggle("normal");
				
			});
			//
			$('#expand_collapse_all').click(function(){
				$(".toggle-content").slideToggle("normal");
				$(".accordion").toggleClass('active');
			});
			//
			//
			$('#select_check_all').click(function(){
				var check_all = $('.select_all').prop('checked');
				if(check_all == true)
				{
					$("input[type=checkbox]").prop("checked", false);	
				}
				else
				{
					$("input[type=checkbox]").prop("checked", true);
				}
			});
			//
			
			$('.select_all').click(function(){
				var checkall = $(this).prop('checked');
				var checkval = $(this).val();
				if(checkall == true)
				{
					$('#'+checkval+' input[type=checkbox]').prop('checked', true);
				}
				else
				{
					$('#'+checkval+' input[type=checkbox]').prop('checked', false);
				}
			});
		});
	</script>
	 <?php
	 }
}

?>