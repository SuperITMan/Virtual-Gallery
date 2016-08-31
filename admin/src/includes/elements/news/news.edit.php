<!-- Add news page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["NEWS"]["EDIT_A_NEWS"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php

            if (!empty($_GET["id"])) :
                $news = getNews($_GET["id"]);
                $newsId = htmlspecialchars($_GET["id"]);

                if (isset($_POST["title"], $_POST["content"])) {
                    editNews($_POST, $newsId);

                } elseif (!empty($_POST["action"]) && !empty($_POST["newsId"])) {
                    if (strcasecmp(htmlspecialchars($_POST["action"]),"delete") == 0) {
                        $deleteStatus = deleteNews($_POST["newsId"]);
                    }
                }

                if (!empty($news)) :
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["NEWS"]["EDITING_A_NEWS_OF_YOUR_WEBSITE"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form id="newNewsForm"
                          class="form-horizontal add-news"
                          role="form" method="POST"
                          action="index.php?p=news&c=edit&id=<?php echo $newsId;?>">

                        <div class="form-group">
                            <label for="title" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["COMMON"]["WORDS"]["TITLE"];?> *
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="title"
                                       name="title"
                                       placeholder="<?php echo $iniLang["COMMON"]["WORDS"]["TITLE"];?>"
                                       class="form-control"
                                       autofocus
                                       value="<?php echo empty($_POST["title"])?$news["title"]:htmlspecialchars($_POST["title"]);?>"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["COMMON"]["WORDS"]["CONTENT"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <textarea rows="4"
                                          id="content"
                                          name="content"
                                          placeholder="<?php echo $iniLang["COMMON"]["WORDS"]["CONTENT"];?>"
                                          class="form-control"
                                          autofocus
                                          required><?php
                                    echo empty($_POST["content"])?$news["content"]:htmlspecialchars($_POST["content"]);
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["EDIT"];?></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <a href="#" onclick="this.preventDefault()"
                                   data-target="#myModal"
                                   data-toggle="modal"
                                   class="btn btn-danger btn-block">
                                    <?php echo $iniLang["COMMON"]["VERBS"]["REMOVE"];?>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><?php echo $iniLang["FORMS"]["ASK_CONFIRMATION"];?></h4>
                                </div>
                                <div class="modal-body">
                                    <p><?php echo $iniLang["FORMS"]["ASK_CONFIRMATION_DELETE"];?></p>
                                </div>
                                <div class="modal-footer">
                                    <form id="modalForm"
                                          method="post"
                                          action="index.php?p=news&c=edit&id=<?php echo $newsId;?>">
                                        <input hidden type="text" name="action" value="delete">
                                        <input hidden type="text" name="newsId" value="<?php echo $newsId;?>">
                                        <button type="submit" class="btn btn-danger">
                                            <?php echo $iniLang["FORMS"]["YES_I_CONFIRM"]; ?>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                else :
                    displayErrorMessage($iniLang["ERROR_MESSAGES"]["PASSED_ID_INCORRECT"]);
                endif;
            else :
                displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_ID_IN_URL"]);
            endif;?>
        </div>
    </div>
</div>