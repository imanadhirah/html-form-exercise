<?php

include("dbconnection.php");
session_start();

$selectUserSql = "SELECT * FROM userData";
$userResult = $connection->query($selectUserSql); //perform query

//perform query for search bar
if(isset($_POST['searchBar'])){ //check whether variable is empty or has been set/declared.

    $searchBar = $_POST['searchBar'];

    $filterUserSql = "SELECT * FROM userData
                    WHERE name like '%$searchBar%' 
                    OR address like '%$searchBar%'
                    OR postcode like '%$searchBar%'
                    OR state like '%$searchBar%'";

    $userResult = $connection->query($filterUserSql); //perform query
}

?>

<!doctype html>
<html class="no-js" lang=""> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Direct Lending Assessment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<script>
     
    // function autofill state
    function autofill(){
    var postcode= $("#postcode").val();

    if(postcode != '')
        {
            $.ajax({
                url:"FetchStateData.php",
                data:{postcode:postcode},
                dataType:"json",
                success:function(data){
                    if(postcode >= 10000)
                    {
                        $("#state").val(data.state_name);
                        document.getElementById("state").disabled = false; 
                        document.getElementById("state").readOnly = true; 
                    }
                    else
                    {
                        document.getElementById("state").value = "";
                        document.getElementById("state").disabled = true; 
                    }
                }
            });
        }
    }

    //edit user
    $(document).on('click', '.editUser', function(){  
           var user_id = $(this).attr("id");  
           $.ajax({  
                url:"FetchUserData.php",  
                method:"POST",  
                data:{user_id:user_id},  
                dataType:"json",  
                success:function(data){  
                     $('#user_id').val(data.user_id);
                     $('#name').val(data.name);
					 $('#dob').val(data.dob);
                     $('#address').val(data.address); 
                     $('#postcode').val(data.postcode);
                     $('#state').val(data.state);

                    //  set elements
                     $('#resetBtn').hide();
                     $('#modal-title').text("Update User Data");
                     $('#saveBtn').val("Update");  
                     $('#postcode').keyup();
                }  
           });  
      });

    // delete user
    $(document).on('click', '.deleteUser', function(){

    var user_id = $(this).attr("id");

    if(user_id != '')
        {
            if(confirm("Are you sure you want to delete this user?")){
                
                $.ajax({
                    url:"DeleteUserData.php",
                    method:"POST",
                    data:{user_id:user_id},
                    success:function(data){
                        window.location.reload();
                    }
                });
            }
            else{
                return false;
            }
        }
    });
</script>

<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Direct Lending Assignment</a>
    </div>
  </div>
</nav>

<div class="container">

 <!-- CONTENT -->
 <div class="content">
            
    <div>
        <h3><i style="margin:0px 10px 0px 10px;"></i>User List</h3>
    </div>

    <!-- Search bar -->
    <form id="reloadpage" method="post" action="MainPage.php">
    <br>
        <div">
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchBar" name="searchBar" placeholder="Search">
            </div>

            <div class="col-md-1">
                <input type="submit" class="btn btn-success btn-block" name="filterBtn" value="Filter"><br>
            </div>

            <div class="col-md-7">
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#add_data_modal">Add New Data</button>
            </div>
            <br><br><br>
        </div>
    </form>
    
    <!-- CONTENT BODY -->
    <div class="card-body">        

        <div class="col-md-12">

            <table class="table table-striped table-bordered">   
            <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Postcode</th>
                    <th>State</th>
                </tr>

                <?php 
                function getAge($dob)
                {
                    $today = date("y-m-d");
                    $diff = date_diff(date_create($dob), date_create($today));
                    $age = $diff->format('%y');

                    return $age;
                }
                
                foreach($userResult as $value){?>
                    <tr>
                    <td>
                        <?=$value['name'];?>
                    </td>
                    <td>
                        <?=getAge($value['dob']);?>
                    </td>
                    <td>
                        <?=$value['address'];?>
                    </td>
                    <td>
                        <?=$value['postcode'];?>
                    </td>
                    <td>
                        <?=$value['state'];?>
                    </td>
                    <td>
                        <a href="#add_data_modal" data-toggle="modal" id="<?=$value['user_id'];?>" class="btn btn-primary btn-sm editUser">Edit</a>
                        <a href="" data-toggle="modal" id="<?=$value['user_id'];?>" class="btn btn-danger btn-sm deleteUser">Delete</a>
                    </td>
                    </tr>
                <?php } ?>

                </table> 
        </div>
        
    </div> <!-- CONTENT BODY END-->

</div>
<!-- CONTENT END -->


<!-- Modal -->
<div class="modal fade" id="add_data_modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" color="#ffffff">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id = "modal-title" class="modal-title">User Data</h4>
            </div>

            <div class="modal-body">
                <div class = "table-responsive">
                    <!-- Form -->
                    <form id="insertDataForm" method="post" action="InsertEditData.php">

                            <input type="hidden" id="user_id" name="user_id" placeholder="User" class="form-control" required ><br/>
                            
                            <label>Name</label> <br/>
                            <input type="text" id="name" name="name" placeholder="Name" class="form-control" required>
                            <br/>
                            <label>Date of Birth</label><br/>
                            <input type="date" id="dob" name="dob" placeholder="yyyy-mm-dd" class="form-control" required>
                            <br />
                            <label>Address</label><br/>
                            <input type="text" id="address" name="address" placeholder="Address" class="form-control" required>
                            <br/>
                            <label>Postcode</label><br/>
                            <input type="number" id="postcode" name="postcode" onkeyup="autofill()" class="form-control" placeholder="Postcode" required>
                            <br/>
                            <label>State</label><br/>
                            <input type="text" id="state" name="state" placeholder="State" class="form-control" required disabled><br/>
                            <br/>

                            <input type="submit" id="saveBtn" class="btn btn-success btn-block" name="submit_data" value="Save" >
                            <input type="reset" id="resetBtn" class="btn btn-danger btn-block" value="Reset">
                            <br />
                    </form>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>