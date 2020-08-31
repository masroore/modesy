<?php
error_reporting(0);
require_once 'functions.php';

$license_code = $_GET['license_code'];
$purchase_code = $_GET['purchase_code'];

if (!isset($license_code) || !isset($purchase_code)) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modesy - Installer</title>
    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font-awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-md-offset-2">

            <div class="row">
                <div class="col-sm-12 logo-cnt">
                    <h1>Modesy</h1>
                    <p>Welcome to the Installer</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="install-box">


                        <div class="steps">
                            <div class="step-progress">
                                <div class="step-progress-line" data-now-value="60" data-number-of-steps="5" style="width: 60%;"></div>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-code"></i></div>
                                <p>Start</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-gear"></i></div>
                                <p>System Requirements</p>
                            </div>
                            <div class="step active">
                                <div class="step-icon"><i class="fa fa-folder-open"></i></div>
                                <p>Folder Permissions</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-database"></i></div>
                                <p>Database</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-user"></i></div>
                                <p>Admin</p>
                            </div>
                        </div>

                        <div class="step-contents">
                            <div class="tab-1">
                                <h1 class="step-title">Folder Permissions</h1>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p><i class="fa fa-folder-open"></i> application/config</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/audios</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/blocks</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/blog</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/category</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/digital-files</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/images</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/images-file-manager</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/logo</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/profile</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/receipts</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/slider</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/temp</p>
                                        <p><i class="fa fa-folder-open"></i> uploads/videos</p>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <p><?php if (is_writable('../application/config')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/audios')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/blocks')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/blog')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/category')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/digital-files')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/images')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/images-file-manager')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/logo')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/profile')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/receipts')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/slider')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/temp')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                        <p><?php if (is_writable('../uploads/videos')) { ?><i class="fa fa-check color-success"></i><?php } else { ?><i class="fa fa-close color-danger"></i><?php } ?></p>
                                    </div>
                                </div>
                                <div class="buttons">
                                    <a href="system-requirements.php?license_code=<?php echo $license_code; ?>&purchase_code=<?php echo $purchase_code; ?>" class="btn btn-success btn-custom pull-left">Prev</a>
                                    <a href="database.php?license_code=<?php echo $license_code; ?>&purchase_code=<?php echo $purchase_code; ?>" class="btn btn-success btn-custom pull-right">Next</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>

