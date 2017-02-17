<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<?php
//var_dump($ticket); exit();
/*
$tk = (object) $ticket;
echo "<pre>";
var_dump($tk);
exit();
*/
//echo "<pre>";
//var_dump($ticket);
//echo "<hr>";
//$obj = (object)$ticket;
//var_dump($obj);

// it is an array inside array
// then simple convert by
// $tk = (object)$ticket;
// will not work coz the result will be
// single object of an array inside

// that why needed foreach here
//foreach ($ticket as $tk) {
    //echo "<hr>";
    //var_dump($tk);
//}

// array inside array
// but only one
$tk = $ticket[0];
//exit();
?>

    <section class="content-header">
        <h1>
            Ticket
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><a href="#">Ticket</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Ticket #<?php echo $tk->id; ?></h3>
            </div>
            <!-- form start -->
            <form class="form-horizontal">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Subject</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $tk->id; ?>">
                                    <input class="form-control" id="subject" placeholder="Subject" value="<?php echo $tk->subject; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Details</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" rows="13" placeholder="Details ..." name="details" id="details"><?php echo $tk->details; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                         <div class="form-group">
                             <label class="col-md-3 control-label">Urgent</label>
                             <!-- <div class="col-md-8" style="background-color: green"> -->
                            <div class="col-md-8">

                                <!-- checkbox -->
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="urgent" id="urgent"<?php echo ($tk->urgent == 1) ? " checked" : NULL; ?>><label for="urgent"> Yes</label>
                                    </label>
                                </div>
                             </div>
                         </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Priority</label>
                                <div class="col-md-8">
                                    <!-- <input class="form-control" placeholder="Priority" value="<?php echo ucfirst($tk->priority); ?>" readonly> -->


                                        <input type="radio" name="priority" id="priority" value="normal"<?php echo ($tk->priority == "normal") ? " checked" : NULL; ?>><label for="normal"> Normal</label>

                                        <input type="radio" name="priority" id="priority" value="medium"<?php echo ($tk->priority == "medium") ? " checked" : NULL; ?>><label for="medium"> Medium</label>

                                        <input type="radio" name="priority" id="priority" value="high"<?php echo ($tk->priority == "high") ? " checked" : NULL; ?>><label for="high"> High</label>

                                    <!-- Minimal red style -->


                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Create By</label>
                                <div class="col-md-8">
                                    <input class="form-control" placeholder="Create by" value="<?php echo $tk->create_by_fname." ".$tk->create_by_lname; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Due Date</label>
                                <div class="col-md-8">

                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right" id="duedate" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $tk->due_date; ?>">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">End User Email</label>
                                <div class="col-md-8">
                                    <input class="form-control" placeholder="End User" name="email" id="email" value="<?php echo $tk->enduser_email; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Project</label>
                                <div class="col-md-8"><select class="form-control" name="project_id" id="project_id">
                                    <?php
                                    foreach ($projects as $project) {
                                        # code...
                                        echo "<option value=\"".$project->id."\"";
                                        echo ($tk->project_id == $project->id) ? " selected" : NULL;
                                        echo ">".$project->project_name."</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Create Date</label>
                                <div class="col-md-8">                            <div class="input-group date">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                              </div>
                                                              <input class="form-control pull-right" id="create_datetime" type="text" value="<?php echo $tk->create_datetime; ?>" readonly>
                                                            </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="pull-right"><button type="button" class="btn btn-warning btn-flat" id="kick_off"<?php
                                    /*
                                        if($tk->state_level == 3){
                                            echo NULL;
                                        }else{

                                        }
                                        */
                                        echo ($tk->state_level == 3) ? NULL : " style=\"display:none\"";
                                        ?> onclick='kick_off_ticket(<?php echo $tk->id; ?>)'>Kick Off</button>
                                        <?php
                                        // if already kicked_off it will can not be updated
                                        if($tk->state_level < 4) {
                                            echo "<input type=\"button\" value=\"Update\" id=\"btn_update\" class=\"btn btn-primary btn-flat\" onclick='do_ticket_update()'>";
                                        } else {
                                            echo "<input type=\"button\" value=\"Update\" id=\"btn_update\" class=\"btn btn-default btn-flat\" disabled>";
                                        }
                                        ?>
                                        <input type="hidden" value="<?php echo $tk->state_level; ?>" id="state_level" name="state_level">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Ticket to Task Approval</h3>
            </div>
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/ticket/approve">
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>No.</th>
                      <th>Task ID.</th>
                      <th>Pick by</th>
                      <!-- <th>Pick Date Time</th> -->
                      <th>Action</th>
                    </tr>
                    <?php

                    $i=0;
                    foreach($picked_by as $p) {
                        echo "<tr>";
                        echo "<td><intput type='hidden' name='pick_id' value=\"".$p->id."\">".++$i."</td>\n";
                        echo "<td>TS#".$p->id."</td>\n";
                        echo "<td>".$p->fname." ".$p->lname."</td>\n";
                        //echo "<td>".$p->pdatetime."</td>\n";
                        //echo '<td><button id="bt" type="button" class="btn btn-primary btn-flat" onclick="dost('.$p->id.','.($i-1).')">Approve</button>';
                        echo "<td>";
                        if($p->task_state > 1) {
                            echo "<input type='button' value='Approved' class='btn btn-default btn-flat disabled'>";
                        } else {
                            echo "<input type='button' value='Approve' id='".$p->id."' class='btn btn-primary btn-flat' onclick='do_approve(".$tk->id.", ".$p->id.", this.id)'>";
                        }
                        echo "</td>";
                        echo "</tr>\n";
                    }

                     ?>
                  </table>
                </div>
                <!-- /.box-body -->

                <script type="text/javascript">

                //
                function kick_off_ticket(ticket_id) {
                    if(window.confirm("After Kick Off, you will no longer be able to modify the Ticket\nAre you sure?")) {
                        //alert("Yeah id="+ticket_id);

                        $.ajax({
                            type:   "post",
                            url:    "/ticket/kick_off",
                            data:   "ticket_id="+ticket_id,
                            error:      function(request, status, error) {
                                console.log(arguments);
                                alert("Can't do because: " + error);
                            },
                            success:    function(data){
                                if(data.match(/success/gi)) {
                                    alert(data);
                                    $('#kick_off').hide();
                                    //$('#btn_update').prop()
                                    $('#btn_update').removeClass("btn-primary").addClass("btn-default");
                                    $('#btn_update').attr("disabled", "disabled");
                                }
                            }
                        });
                    }
                }
                //$(document).ready(function(){
                    //$('#kick_off').hide();
                    //$("#button").click
                //});

                // do_approve function
                // recieve pick_id or task_id, ticket_id and this->button_id
                function do_approve(ticket_id, pick_id, id){
                    //var x = 'ip=' + ip + ' id=' + id;
                    var button_id = '#'+id;
                    var state_level = $("#state_level").val();
                    //$('#kick_off').hide();
                    //alert('pick_id='+pick_id+'\nbutton_id='+button_id);
                    //$(button_id).prop('value', 'Approved');
                    //$(button_id).removeClass("btn-primay").addClass("btn-warning");
                    //$('#kick_off').show();

                    // ajax

                    $.ajax({
                        type:       "post",
                        url:        "/ticket/approve",
                        data:       "pick_id="+pick_id+"&ticket_id="+ticket_id+"&state_level="+state_level,
                        error:      function(req, err) {
                            console.log('my message: '+err);
                            alert("Can't do because: "+err);
                        },
                        success:    function(data){
                            if(data.match(/success/gi)) {
                                $('#kick_off').show();
                                $(button_id).prop('value', 'Approved');
                                $(button_id).removeClass("btn-primary").addClass("btn-warning");
                            }
                            alert(data);
                        }
                    });

                    //alert("test");

                    //$(y).html('<span class="glyphicon glyphicon-star" aria-hidden="true"></span>Approved');
                    return true;
                }

                // this function shoud update many details but for NOW
                // allow to update only
                // - End User Email
                // - Project ID

                // 2017-01-14 Now update

                function do_ticket_update(){
                    var subject     = $("#subject").val();
                    var details     = $("#details").val();
                    var email       = $("#email").val();
                    var project_id  = $("#project_id").val();
                    var ticket_id   = $("#ticket_id").val();
                    var duedate     = $("#duedate").val();
                    //var priority    = $("#priority").val();
                    //var urgent      = $("#urgent").val();

                    // we work with radio
                    var priority = $("[name='priority']:checked").val();

                    // we treate with checkbox diffenly
                    if($("#urgent").is(":checked")) {
                        //alert("It urgent!")
                        urgent = 1;
                    } else {
                        urgent = 0;
                    }
                    //alert("urgent="+urgent);

                    if(email == "" || project_id == "" || subject == "" || details == "" || duedate == "") {
                        alert('Please enter Subject, Details, Email, Project and Duedate Field to update this Ticket');
                    } else {
                        $.ajax({
                            type:       "post",
                            url:        "/ticket/update",
                            data:       "subject="+subject+"&details="+details+"&ticket_id="+ticket_id+"&email="+email+"&project_id="+project_id+"&priority="+priority+"&urgent="+urgent+"&duedate="+duedate,
                            error:      function(request, status, error){
                                console.log(arguments);
                                alert("Can't do because: "+error);
                            },
                            success:    function(data){
                                //var a=data.search(/success/i);
                                //alert("data="+data+"\na="+a);
                                // looking for "success" in data
                                if(data.match(/success/gi)) {
                                    $("#btn_update").removeClass("btn-primary").addClass("btn-success");
                                    $("#btn_update").prop('value', 'Updated');
                                    //$("#btn_update").attr("disabled", "disabled");
                                }
                                alert(data);
                            }
                        });
                        //alert('Yo! E='+email+' P='+project_id);
                    }
                    return true;
                }
                </script>
                <!-- /.box-footer -->
            </form>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12">&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div class="box">
            <pre><?php
            // debug
            //echo var_dump($picked_by); ?></pre>
        </div>
        -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
