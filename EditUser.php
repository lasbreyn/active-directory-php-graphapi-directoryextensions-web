<?php
  include("header.php");

      // If this was not a post back show the edit user form
   if (!isset($_POST['submit'])) {
      include("navigation.php");
      $user = GraphServiceAccessHelper::getEntry('users',$_GET['id']);
      $skypeAccount = isset($user->{Settings::$skypeExtension}) ? $user->{Settings::$skypeExtension} : "";
//            echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id']. '">');
      echo('<form id="CreateUserForm" method="post" action="'.$_SERVER['PHP_SELF'].'">');
      echo('<table>');
      echo('<tr><td><b>Display Name:</b></td><td><input class="form-control" type="text" size="20" maxlength="100" name="dname" value="'. $user->{'displayName'}.'"></td></tr>');
      echo('<tr><td><b>Mail Alias:</b></td><td><input class="form-control" type="text" size="20" maxlength="15" name="alias" value="'. $user->{'mailNickname'}.'"></td></tr>');
      echo ('<input name="id" type="hidden" value='.$_GET['id'].'>');
      echo ('<input type="hidden" name="action" value="createExtension" />');
      echo('<tr><td><b>Skype Account:</b></td><td><input class="form-control" type="text" size="20" maxlength="15" name="skypeAccount" value="'. $skypeAccount.'"></td></tr>');
      echo('<tr><td><b>Title:</b></td><td><input class="form-control" type="text" size="20" maxlength="15" name="jobTitle" value="'. $user->{'jobTitle'}.'"></td></tr>');

      $extensions = GraphServiceAccessHelper::getFeed('applications/'.Settings::$appObjectId.'/extensionProperties');
      if (isset($extensions)) {
        foreach ($extensions as $extension){
          if(in_array("User", $extension->targetObjects)) {
            $ext_array = explode('_', $extension->name);
            $extension_name =  array_pop($ext_array);
            $extension_value =  (isset($user->{$extension->name}) && !empty($user->{$extension->name})) ? $user->{$extension->name} : '';
            echo('<tr><td><b>'. $extension_name. ':</b></td><td><input class="form-control" type="text" size="20" maxlength="15" name="'.$extension->name.'" value="'.$extension_value.'"></td></tr>');
          }
        }
      }

      echo('<tr><td></td><td><input type="submit" class="btn btn-primary" value="submit" name="submit"></td></tr>');

      echo('</table>');
      echo('</form>');

      include("footer.php");
   }
   else {
        if((empty($_POST["dname"])) or (empty($_POST["alias"]))) {
            echo('<p>One of the required fields is empty. Please go back to <a href="EditUser.php'.'?id='.$_POST['id'].'">Update User</a></p>');
        }
        else {
            $displayName = $_POST["dname"];
            $alias = $_POST["alias"];
            $skypeAccount = $_POST["skypeAccount"];
            $jobTitle = $_POST["jobTitle"];
            $userEntryInput = array(
                'displayName'=> $displayName,
                'userPrincipalName' => $alias.'@'.Settings::$appTenantDomainName ,
                'mailNickname' => $alias,
                 Settings::$skypeExtension => $skypeAccount,
                'jobTitle' => $jobTitle);

            $extensions = GraphServiceAccessHelper::getFeed('applications/'.Settings::$appObjectId.'/extensionProperties');
            if (isset($extensions)) {
              foreach ($extensions as $extension){
                if(in_array("User", $extension->targetObjects) && array_key_exists($extension->name, $_POST)) {
                  $userEntryInput[$extension->name] = $_POST[$extension->name];
                }
              }
            }
            // Update the user
            $user = GraphServiceAccessHelper::updateEntry('users',$_POST['id'],$userEntryInput);

            //Check to see if we got back an error.
            if(!empty($user->{'odata.error'}))
            {
                $message = $user->{'odata.error'}->{'message'};
                echo('<p>User update failed. Service returned error:<b>'.$message->{'value'}. '</b> Please go back to <a href="EditUser.php'.'?id='.$_POST['id'].'">Update User</a></p>');
            }
            else {
                header('Location: DisplayUsers.php');
            }
       }
   }

?>
