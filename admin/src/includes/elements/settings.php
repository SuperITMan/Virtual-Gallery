<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["SETTINGS"]["TITLE"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if(!empty($_POST["siteName"]))
                editSettings($_POST);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["SETTINGS"]["SITE_SETTINGS"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    $settings = getAllSettings();
                    if ($settings):
                    ?>
                    <form id="settingsForm" class="form-horizontal settings-edit" role="form" method="POST" action="index.php?p=settings">
                        <div class="form-group">
                            <label for="siteName" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["SETTINGS"]["SITE_NAME"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="siteName"
                                       name="siteName"
                                       placeholder="<?php echo $iniLang["SETTINGS"]["SITE_NAME"];?>"
                                       class="form-control"
                                       autofocus
                                       value="<?php echo empty($_POST["siteName"])?
                                           (empty($settings["siteName"])?"":$settings["siteName"]):htmlspecialchars($_POST["siteName"]);?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="copyright" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["SETTINGS"]["COPYRIGHT"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="copyright"
                                       name="copyright"
                                       placeholder="<?php echo $iniLang["SETTINGS"]["COPYRIGHT"];?>"
                                       class="form-control"
                                       autofocus
                                       value="<?php echo empty($_POST["copyright"])?
                                           (empty($settings["copyright"])?"":$settings["copyright"]):htmlspecialchars($_POST["copyright"]);?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="aboutUs" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["SETTINGS"]["ABOUT_US"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <textarea rows="4"
                                          id="aboutUs"
                                          name="aboutUs"
                                          placeholder="<?php echo $iniLang["SETTINGS"]["ABOUT_US"];?>"
                                          class="form-control"
                                          autofocus><?php
                                    echo empty($_POST["aboutUs"])?
                                        (empty($settings["aboutUs"])?"":$settings["aboutUs"]):htmlspecialchars($_POST["aboutUs"]);
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["EDIT"];?></button>
                            </div>
                        </div>
                    </form>
                    <?php
                    else:
                        displayErrorMessage($iniLang["ERROR_MESSAGES"]["NOT_ENOUGH_RIGHTS_TO_ACCESS_THIS_PAGE"]);
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>