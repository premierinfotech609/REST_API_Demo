<?php
    include_once 'config/Credentials.php';
    set_time_limit(0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>REST API Demo</title>
    <script language="javascript" src="jquery-3.2.1.min.js"></script>
    <script language="javascript" src="jquery.blockUI.js"></script>

    <style>
        button, p{
            cursor: pointer;
        }
    </style>
    <script>

        $(document).ready(function(){
            readData();
        });

        function showAddUserDiv(){
            $('#addUserDiv').toggle();
        }
        function showUserDetails(userID){
            $('#detailTr'+userID).toggle();
        }
        
        function readData(){
            $('#addUserDiv').hide();
            var api_path = '<?php echo API_PATH; ?>';
            console.log(api_path);
            var api_key = '<?php echo API_KEY; ?>';
            $.blockUI({ message: '<h1>Fetching User Data...</h1>' });
            $.ajax({
                type: 'GET',
                url: api_path+'read.php',
                data:{"api_key": api_key},
                cache:false,
                success: function(data, status) {
                    var htmlData = '';
                    $('#usersDataTable').html("");
                    $('#usersDataTable').append('<tr><th>Action</th><th>First Name</th><th>Increase</th><th>Decrease</th><th>Total Points</th></tr>');
                    if(data !== undefined){
                        $.each(data['users'],function(key,value){
                            htmlData += '<tr><td><button type="button" name="deleteUser" id="deleteUser'+value["id"]+'" value="deleteUser" onclick="deleteUser('+value["id"]+')">X</button></td><td><p onclick="showUserDetails('+value["id"]+')">'+value["first_name"]+'</p></td><td><button type="button" name="increasePoints" id="increasePoints'+value["id"]+'" value="1" onclick="updatePoints(1,'+value["id"]+','+value["points"]+')">+</button></td><td><button type="button" name="decreasePoints" id="decreasePoints'+value["id"]+'" value="-1" onclick="updatePoints(-1,'+value["id"]+','+value["points"]+')">-</button></td><td>'+value["points"]+' points</td></tr><tr style="display:none" rowspan="4" id="detailTr'+value["id"]+'"><td colspan="5">Name: '+value["first_name"]+'</br>Age: '+value["age"]+'</br>Points: '+value["points"]+'</br>Address: '+value["address"]+'</br></td></tr>';
                        });

                        $('#usersDataTable').append(htmlData);
                    }
                    $('#usersDataTable').append('<tr ><td colspan="5"><button style="float:right" name="addUser" id="addUser" value="+ Add User" onclick="return showAddUserDiv()">+ Add User</button></td></tr>');
                    $.unblockUI();
                },
                error: function(xhr, desc, err) {
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
            });
        }
        function createUser(){
            var api_path = '<?php echo API_PATH; ?>';
            var api_key = '<?php echo API_KEY; ?>';
            var first_name = $('#first_name').val();
            var age = $('#age').val();
            
            $.blockUI({ message: '<h1>Creating New User...</h1>' });
            $.ajax({
                type: 'POST',
                url: api_path+'create.php',
                data:{"api_key": api_key,"first_name": first_name,"age": age},
                dataType: "JSON",
                cache:false,
                success: function(data, status) {
                    alert(data["message"]);
                    readData();
                    $.unblockUI();
                },
                error: function(xhr, desc, err) {
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
            });
        }

        function updatePoints(change,userID,currentPoints){
            var api_path = '<?php echo API_PATH; ?>';
            var api_key = '<?php echo API_KEY; ?>';
            $.blockUI({ message: '<h1>Updating User Points...</h1>' });
            $.ajax({
                type: 'POST',
                url: api_path+'update.php',
                data:{"api_key": api_key,"id": userID,"points":currentPoints+change},
                cache:false,
                success: function(data, status) {
                    alert(data["message"]);
                    readData();
                    $.unblockUI();
                },
                error: function(xhr, desc, err) {
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
            });
        }
        function deleteUser(userID){
            var api_path = '<?php echo API_PATH; ?>';
            var api_key = '<?php echo API_KEY; ?>';
            $.blockUI({ message: '<h1>Deleting User...</h1>' });
            $.ajax({
                type: 'POST',
                url: api_path+'delete.php',
                data:{"api_key": api_key,"id": userID},
                cache:false,
                success: function(data, status) {
                    alert(data["message"]);
                    readData();
                    $.unblockUI();
                },
                error: function(xhr, desc, err) {
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
            });
        }
    </script>
</head>
<body>
    <table align="center" id = "usersDataTable" border="10px" cellpadding="10" cellspacing="0" style="margin-top: 100px; margin-bottom: 100px;" >
        
    </table>
    <form name='addUserForm' id='addUserForm' method='post'>
        <table id="addUserDiv" style="display:none;" name="addUserDiv" border="10px" align="center" cellpadding="10" cellspacing="0" >
                <tr>
                    <td>First Name: </td>
                    <td><input type='text' name="first_name" id="first_name" value="" placeholder="Please Enter First Name" maxvalue="255" /></td>
                </tr>
                <tr>
                    <td>Age: </td>
                    <td><input type='text' name="age" id="age" value="" placeholder="Please Enter Age" maxvalue="999" /></td>
                </tr>
                <tr>
                    <td colspan="2"><button style="float:right" type="button" name="Submit" id="Submit" value="Create User" onclick='return createUser();'>Create User</button></td>
                </tr>
        </table>
    </form>
</body>
</html>