<?php
    include("header.php");
    include("navigation.php");
?>
        <h1>
            /applications
        </h1>
        <br/><br/>
        <table id="directoryObjects">
            <tr>
            <th>Name</th>
            <th>ObjectId</th>
            <th>AppPrincipalId</th>
            </tr>
            <?php
                $applications = GraphServiceAccessHelper::getFeed('applications');
                if (isset($applications))
                {
                    foreach ($applications as $applications){
                        echo('<tr><td>'. $applications->{'displayName'}. '</td><td>'. $applications->{'objectId'} .'</td><td>'. $applications->{'appId'}.'</td></tr>');
                    }
                }
            ?>
        </table>
<?php include("footer.php"); ?>
