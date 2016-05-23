<?php
    include("header.php");
    include("navigation.php");
 ?>
    <h1>
        /Users
    </h1>
    <a class="btn btn-primary" role="button" href="CreateUser.php"><b>Create And Add A New User</b></a>
    <br/><br/>
    <table id="directoryObjects">
        <tr>
        <th>Display Name</th>
        <th>Email</th>
        <th>Title</th>
        <th>Skype Account</th>
        <th>Edit Link</th>
        </tr>
        <?php
            $users = GraphServiceAccessHelper::getFeed('users');
            foreach ($users as $user){
                $editLinkValue = "EditUser.php?id=".$user->objectId;
                $skypeAccount = isset($user->{Settings::$skypeExtension})
                 ?  '<a href="skype:'.$user->{Settings::$skypeExtension}.'?call">'.$user->{Settings::$skypeExtension}.'</a>'
                 : "not set";
                echo('<tr><td>'. $user->{'displayName'}. '</td><td>'. $user->{'userPrincipalName'} .'</td><td>'. $user->{'jobTitle'} .'</td>');
                echo ('<td>'.$skypeAccount.'</td>');
                echo('<td>' .'<a class="btn btn-secondary" role="button" href=\''.$editLinkValue.'\'>'. 'Edit User' . '</a></td></tr>');
            }
        ?>
    </table>
<?php include("footer.php"); ?>
