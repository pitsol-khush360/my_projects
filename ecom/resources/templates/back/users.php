<div class="col-lg-12">

    <h1 class="page-header">Users</h1>
        <p class="bg-success">
            <?php displaymessage(); ?>
        </p>
</div>
<div class="col-lg-12">
                <div class="col-md-12">
                    <a href="add_user.php" class="btn btn-primary">Add User</a>
                    <p></p>
                        <table class="table table-hover table-bordered">
                            <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name </th>
                                        <th>Address</th>
                                        <th>Mobile Number</th>
                                        <th>Profile Picture</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php show_users(); ?>
                                    <!--<tr>
                                        <td>2</td>
                                        <td><img class="admin-user-thumbnail user_image" src="placehold.it/62x62" alt=""></td>
                                        <td>Rico
                                            <div class="action_links">
                                                <a href="">Delete</a>
                                                <a href="">Edit</a>  
                                            </div>
                                        </td>
                                        <td>Edwin</td>
                                       <td>Diaz</td>
                                    </tr>    -->       
                                </tbody>
                        </table> <!--End of Table-->
                </div>
</div>