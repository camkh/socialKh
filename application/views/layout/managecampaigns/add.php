<?php if ($this->session->userdata('user_type') != 4):
    if(!empty($data)):
        foreach ($data as $value):
            $dataConents = $value->{Tbl_posts::conent};
            $json = json_decode($dataConents, true);
            $Thumbnail = $json['image'];
            $postTitle = $json['title'];
            $postLink = $json['link'];
        endforeach;
    endif;
    $post_id = !empty($_GET['id'])?$_GET['id']:'';
    ?>
    <style>
        .radio-inline{}
        .error {color: red}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" class="form-horizontal row-border">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <input name="submit" type="submit" value="Publish" class="btn btn-primary pull-right" /><h4>
                            <i class="icon-reorder">
                            </i>
                            Add New Post
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="title">Title:</label>
                                    <div class="col-md-8">
                                        <input type="text" value="<?php echo @$postTitle; ?>" name="title" id="title" class="required form-control" />
                                        <input type="hidden" value="<?php echo @$post_id; ?>" name="postid" id="postID"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 control-label">
                                        <label for="message">
                                            Message:
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea class="limited form-control" name="message" cols="5" rows="3" id="message"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="caption">Caption:</label>
                                    <div class="col-md-8">
                                        <input type="text" value="<?php echo @$postTitle; ?>" name="caption" id="caption" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="link">Link</label>
                                    <div class="col-md-8">
                                        <input type="text" value="<?php echo @$postLink; ?>" name="link" id="link" class="form-control required" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Groups">Facebook account to use:</label>
                                    <div class="col-md-8">
                                        <select name="accoung" class="form-control required" id="Groups" required>
                                            <option value="">Select Account</option>
                                            <?php foreach ($account as $vAccount): ?>
                                            <option value="<?php echo $vAccount->{Tbl_social::s_id}; ?>|<?php echo $vAccount->{Tbl_social::s_type}; ?>"><?php echo $vAccount->{Tbl_social::s_name}; ?> [@ <?php echo $vAccount->{Tbl_social::s_type}; ?> ]</option>
                                            <?php endforeach;?>
                                        </select>
                                        <label id="showgroum" for="imagepost" generated="true" class="error help-block" style="display: none;">please select one.</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Stacked Radio Buttons:
                                    </label>
                                    <div class="col-md-8"> 
                                        <label class="radio"> 
                                            <input type="radio" name="postto" value="wall" id="towall" class="required" required/> 
                                            On main Facebook account wall 
                                        </label> 
                                        <label class="radio"> 
                                            <input type="radio" name="postto" value="groups" id="togroup" class="required" required/> 
                                            On groups
                                        </label> 
                                    </div> 
                                </div>
                                <div class="form-group" id="groupWrapLoading" style="display: none; text-align: center; font-size: 130%;color:red;">Loading...</div>
                                <div class="form-group" id="groupWrap" style="display: none;">
                                    <div class="col-md-4">
                                        <label class="checkbox"> 
                                        <input type="checkbox" value="" id="checkAll"/> 
                                        The campaign will post (CHECK/UNCHECK):
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="getAllGroups" style="max-height: 250px;overflow-y: auto; margin-right:10px"></div>
                                        <button type="button" value="add" id="addGroups" class="btn btn-primary pull-right" style="margin-right:10px;margin-top:10px">Add</button>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <img src="<?php echo @$Thumbnail; ?>" class="img-thumbnail" />
                                        <input type="hidden" value="<?php echo @$Thumbnail; ?>" name="thumb" class="required" />                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Status:
                                    </label>
                                    <div class="col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" value="1" name="videotype" checked="checked" />
                                            <i class="subtopmenu hangmeas">Active</i>
                                        </label> 
                                        <label class="radio-inline">
                                            <input type="radio" value="0" name="videotype" />
                                            <i class="subtopmenu hangmeas">Draff</i>
                                        </label>                                
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <label class="control-label">
                                        Schedule:
                                    </label>
                                    <div style="clear:both"></div>
                                        <input type="text" value="<?php echo date("m/d/Y");?>" name="startDate" class="form-control " id="datepicker" size="10" placeholder="Start date"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                        <input type="text" value="<?php echo date("h:i A");?>" name="startTime" class="form-control " id="timepicker" size="10" placeholder="start time"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                        <span style="float: left;margin-right: 5px;">to </span>  
                                        <input type="text" name="endDate" class="form-control " id="datepickerEnd" size="10" placeholder="End date"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                        <input type="text" name="endTime" class="form-control " id="timepickerEnd" size="10" placeholder="end time"  style="float:left;margin-right:5px;height:25px; width:85px"/>
                                    <div style="clear:both"></div>
                                    <label class="control-label">
                                        Repeat:
                                    </label>
                                    <label class="radio"> 
                                        <input type="radio" name="loop" value="m" id="everyMimute"/> 
                                        <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                        <input class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="120"/> minutes
                                    </label>
                                    <div style="clear:both"></div>
                                    <label class="radio"> 
                                        <input type="radio" name="loop" value="h" id="everyHour"/> 
                                        <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                        <input class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="1"/> hour
                                    </label>
                                    <div style="clear:both"></div>
                                    <label class="radio"> 
                                        <input type="radio" name="loop" value="d" id="everyDay" checked="checked"/> 
                                        <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                        <input class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="1"/> day
                                    </label>
                                    <div style="clear:both"></div>
                                    <label class="control-label">
                                        Repeat on:
                                    </label>
                                    <div class="col-md-12">
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Sun" name="loopDay[]" checked="checked"/>
                                            S 
                                        </label> 
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Mon" name="loopDay[]" checked="checked"/>
                                            M 
                                        </label>
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Tue" name="loopDay[]" checked="checked"/>
                                            T 
                                        </label>
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Wed" name="loopDay[]" checked="checked"/>
                                            W 
                                        </label>
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Thu" name="loopDay[]" checked="checked"/>
                                            T
                                        </label>
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Fri" name="loopDay[]" checked="checked"/>
                                            F
                                        </label>
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" class="uniform" value="Sat" name="loopDay[]" checked="checked"/>
                                            S
                                        </label>
                                    </div>
                                     <div style="clear:both"></div>
                                     <label class="control-label">
                                        Ends:
                                    </label>
                                    <div class="col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" value="1" name="looptype" class="required" checked="checked" />
                                            <i class="subtopmenu hangmeas">Loop</i>
                                        </label> 
                                        <label class="radio-inline">
                                            <input type="radio" value="0" name="looptype" class="required" />
                                            <i class="subtopmenu hangmeas">Once</i>
                                        </label>                                
                                    </div>
                                    
                                    <label class="control-label">
                                        Pause between posting the messages:
                                    </label>
                                    <div style="clear:both"></div>
                                        <label class="radio"> 
                                        <input type="radio" name="loop" value="h" id="everyHour"/> 
                                        <span style="float: left;margin-right: 5px;">Repeat every: </span> 
                                        <input class="form-control input-width-mini" type="text" style="float:left;margin-right:5px;height:25px" value="1"/> [recommended value: 1 minute]
                                    </label>
                                    <div style="clear:both"></div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="submit" type="submit" value="Public Content" class="btn btn-primary pull-right" />
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>

    </div>
    <script>
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
    </script>

    <?php
 else:
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
endif;
?>