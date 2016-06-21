 <!--start of the tags-->
                            <div class="box-item list-item">
                                <span class="control">

                                    <a id="tags_link" onclick="tagsControl()" class="edit <?php echo $editIcon; ?>
                                      <?php echo Loader::getHelper('companies/data')->getControlsClass() ?>">
                                       Edit</a>
                               </span>

                                <p class="box-title">
                                    Tags
                                </p>
                                <input type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo" data-role="tagsinput" >
                               <div class="box-content">
                                    <div id="tags_show">
                                        <?php
                                        $str = $this->info['tags'];
                                        $tags_arr = explode(",", $str);
                                       foreach ($tags_arr as $i) {
                                            echo $i . "\t";
                                        }
                                        ?>  
                                    </div>
                                    <div id="tags_edit" hidden>
                                        <br/>
                                        <form name="editTagsFrm" method="POST" 
                                              action="<?php echo ROOT_URL ?>/companies/profile/save-tags" novalidate="novalidate">
                                               
                                        <input type="hidden" name="company_id" value="<?php echo $this->info['id'] ?>"/>
                                            <?php for ($t = 0; $t < 10; $t++) { ?>
                                            <input type="text" class="col-sm-6 form-control"  placeholder="tags"
                                                   name="<?php echo "tags_".$t;?>"
                                                       value="<?php if(isset($tags_arr[$t])){echo $tags_arr[$t];}?>"> <br/>                                          
                                            <?php } ?>
                                                <br/>  <br/>
                                                <input type="submit" style="color:white"
                                                       class="btn-success btn-block" value="Submit">
                                        </form>
                                    </div>
                               </div>
                            </div>

                            <!--end of tags-->