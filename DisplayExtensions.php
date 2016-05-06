<?php
    include("header.php");
    // Load json data for default eduPerson details
    $data = file_get_contents ("accets/defaults.json");
    $json = json_decode($data, true);
?>
    <h1>/extensions</h1>
    <div class="clearfix">
        <a id="CreateExtension" class="clearfix btn btn-primary" role="button" href="CreateExtension.php"><b>Create And Add A New extension</b></a>
    </div>
    <div class="alert alert-success">
        <strong>Success!</strong>
    </div>
<!--    Add extension form-->
    <div id="CreateExtensionForm-wrapper" class="panel panel-default">
        <div class="panel-heading clearfix"><h4 class="header-title">Create an Extension</h4><button type="button" class="cancel-btn btn btn-warning">Cancel</button></div>
        <div class="panel-body">
            <form id="CreateExtensionForm" method="post" role="form" action="controller.php">
                <table>
                    <tr><td><b>Name: </b></td><td><input type="text" size="20" maxlength="15" name="extensionName" required></td></tr>
                    <tr><td><b>Data Type: </b></td><td><select name="dataType" required><option>String</option><option>Binary</option></select></td></tr>
                    <tr><td><b>Target Objects: </b></td><td><input type="checkbox" name="targetObjects[]" value="User" required>User</td></tr>
                    <tr><td></td><td><input type="checkbox" name="targetObjects[]" value="TenantDetail" required>TenantDetail</td></tr>
                    <tr><td></td><td><input type="checkbox" name="targetObjects[]" value="Group" required>Group</td></tr>
                    <tr><td></td><td><input type="checkbox" name="targetObjects[]" value="Device" required>Device</td></tr>
                    <tr><td></td><td><input type="checkbox" name="targetObjects[]" value="Application" required>Application</td></tr>
                    <tr><td></td><td><input type="checkbox" name="targetObjects[]" value="ServicePrincipal" required>Service Principal</td></tr>
                    <tr><td></td><td><br/><input type="submit" class="btn btn-primary" value="submit" name="submit"></td></tr>
                </table>
            </form>
        </div>
    </div>
<!--    Listing of enabled extensions-->
    <h3>Enabled</h3>
    <table id="directoryObjects">
        <tr>
        <th>Name</th>
        <th>Data Type</th>
        <th>Target Objects</th>
        </tr>
        <?php
            $extensions = GraphServiceAccessHelper::getFeed('applications/'.Settings::$appObjectId.'/extensionProperties');
            if (isset($extensions) && count($extensions) > 0)
            {
                foreach ($extensions as $extension){
                    $extension_info = 'objectId='.$extension->{'objectId'};
                    echo('<tr><td>'. $extension->{'name'}. '</td><td>'. $extension->{'dataType'} .'</td><td>'. implode(", ", $extension->{'targetObjects'}) .'
                    <form id="'.$extension->{'name'}.'" method="post" action="controller.php?action=deleteExtension&data='.$extension_info.'">
                    <input type="hidden" name="objectId" value="'.$extension->{'objectId'}.'" />
                    <input type="submit" class="btn btn-default right" value="Disable"/>
                    </form>
                    </td></tr>');
                }
            } else {
                echo ('<tr><td colspan="3"><p>There are no enabled extensions. You may enable any of the eduPerson extensions below or click "Create and Add A New extension" to add yours.</td></tr>');
            }

        ?>
    </table>
    <br/><br/>
    <h3>eduPerson Extensions Available</h3>
<!--    Default extensions-->
    <?php if($json): ?>
        <table class="table table-striped table-hover table-bordered CreateExtensionDefaultsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Data Type</th>
                    <th>Target Objects</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $block = '';
                $passed = array();
                foreach ($extensions as $extension_enabled) {
                    $name_arr = explode('_', $extension_enabled->name);
                    $name = array_pop($name_arr);
                    array_push($passed, $name);
                }
                foreach ($json['eduPersonAttributes'] as $extension) {

                    if(!in_array($extension['name'], $passed)) {
                        $block .= '<tr>';
                        $block .= '<td><b>'.$extension['label'].'</b></td>';
                        $block .= '<td>';
                        $block .= '<select name="dataType">';
                        foreach ($extension['dataType'] as $key => $target) {
                            $block .= '<option value="'.$target.'" selected="selected">'.$target.'</option>';
                        }
                        $block .= '</select>';
                        $block .= '</td>';
                        $block .= '<td>';
                        foreach ($extension['targetObjects'] as $key => $target) {
                            $block .= '<input type="checkbox" name="targetObjects[]" value="'.$target.'" checked="checked"> '.$target.'</br>';
                        }
                        $block .= '</td>';
                        $block .= '<td>';
                        $block .= '<button type="button" class="btn btn-default" name="'. $extension['name'].'" value="'. $extension['name'].'">Enable</button>';
                        $block .= '</td>';
                        $block .= '</tr>';
                    }
                }
                echo $block;
            ?>
            </tbody>
        </table>
    <?php endif; ?>
<!--    </form>-->

<?php include("footer.php"); ?>