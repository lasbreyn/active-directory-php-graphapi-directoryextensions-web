<?php
  require_once 'Settings.php';
  require_once 'GraphServiceAccessHelper.php';
  require_once 'AuthorizationHelperForGraph.php';

    echo "<pre>";
    print_r($_POST);
    echo "</pre>"

  $action = $_REQUEST['action'];
  $params = array();
  parse_str($_REQUEST['data'], $params);
  $data = array();

  switch ($action) {
    case "createExtension":
      if((empty($params["extensionName"])) or (empty($params["dataType"])) or (empty($params["targetObjects"]))) {
        $data['success'] = FALSE;
        $data['message'] = 'One of the required fields is empty';
      } else {
        $name = $params["extensionName"];
        $dataType = $params["dataType"];
        $targetObjects = $params["targetObjects"];
        $extension = array(
        'name'=> $name,
        'dataType' => $dataType ,
        'targetObjects' => $targetObjects);

        // Create the extension
        $extensionCreated = GraphServiceAccessHelper::addEntryToFeed('applications/'.Settings::$appObjectId.'/extensionProperties',$extension);

        // Check to see if we got back an error.
        if(!empty($extensionCreated->{'odata.error'})) {
          $message = $extensionCreated->{'odata.error'}->{'message'};
          $data['success'] = FALSE;
          $data['message'] = 'Extension creation failed. Service returned error: '.$message->{'value'};
        } else {
          $data['success'] = true;
          $data['message'] = 'Success!';
        }
      }
      echo json_encode($data);
    break;
    case "deleteExtension":
      if((empty($params["objectId"]))) {
        $data['success'] = FALSE;
        $data['message'] = 'One of the required fields is empty';
      } else {
        $name = $params["objectId"];
        // Delete the extension
        $extensionCreated = GraphServiceAccessHelper::deleteEntry('applications/'.Settings::$appObjectId.'/extensionProperties',$name);

        // Check to see if we got back an error.
        if(!empty($extensionCreated->{'odata.error'})) {
          $message = $extensionCreated->{'odata.error'}->{'message'};
          $data['success'] = FALSE;
          $data['message'] = 'Extension creation failed. Service returned error: '.$message->{'value'};
        } else {
          $data['success'] = true;
          $data['message'] = 'Success!';
        }
      }
      echo json_encode($data);
      break;
    case "createUser":
    //      code to be executed if n=label2;
    break;
    case "editUser":
      if((empty($params["dname"])) or (empty($params["alias"]))) {
        $data['success'] = FALSE;
        $data['message'] = 'One of the required fields is empty';
      }
      else {
        $displayName = $params["dname"];
        $alias = $params["alias"];
        $skypeAccount = $params["skypeAccount"];
        $jobTitle = $params["jobTitle"];

        $userEntryInput = array(
          'displayName'=> $displayName,
          'userPrincipalName' => $alias.'@'.Settings::$appTenantDomainName ,
          'mailNickname' => $alias,
          'jobTitle' => $jobTitle,
          Settings::$skypeExtension => $skypeAccount,
          'extension_35459ae9fc814beab19db44537fc0472_eduPersonEntitlement' => 'Test entitlemen');
        // Process all extensions

        $extensions = GraphServiceAccessHelper::getFeed('applications/'.Settings::$appObjectId.'/extensionProperties');
        if (isset($extensions)) {
          foreach ($extensions as $extension){
            if(in_array("User", $extension->targetObjects) && array_key_exists($extension->name, $params)) {
              $userEntryInput[$extension->name] = $params[$extension->name];
            }
          }
        }
        // Update the user
        $user = GraphServiceAccessHelper::updateEntry('users',$params['id'],$userEntryInput);
        //Check to see if we got back an error.
        if(!empty($user->{'odata.error'}))
        {
          $message = $user->{'odata.error'}->{'message'};
          $data['success'] = FALSE;
          $data['message'] = 'User update failed. Service returned error '.$message->{'value'};
        }
        else {
          $data['success'] = true;
          $data['message'] = 'Success!';
        }
      }
      echo json_encode($data);
    break;
    default:
    //      code to be executed if n is different from all labels;
  }
