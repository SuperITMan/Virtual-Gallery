<!-- Add news page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["NEWS"]["ADD_A_NEWS"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (isset($_POST["title"], $_POST["content"])) {
                if($newsId = addNews($_POST)) {
                    displaySuccessMessage("L'ajout s'est passé avec succès ! id:".$newsId);
                    $_POST = array();
                }
                else
                    displayErrorMessage("Il y a eu un problème...");
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["NEWS"]["ADDING_OF_A_NEWS_ON_YOUR_WEBSITE"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form id="newNewsForm" class="form-horizontal add-news" role="form" method="POST" action="index.php?p=news&c=add">

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
                                       value="<?php echo empty($_POST["title"])?"":htmlspecialchars($_POST["title"]);?>"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["COMMON"]["WORDS"]["CONTENT"];?> *
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <textarea rows="4"
                                          id="content"
                                          name="content"
                                          placeholder="<?php echo $iniLang["COMMON"]["WORDS"]["CONTENT"];?>"
                                          class="form-control"
                                          autofocus
                                          required><?php
                                    echo empty($_POST["content"])?"":htmlspecialchars($_POST["content"]);
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
                                <button type="submit" class="btn btn-primary btn-block"><?php echo $iniLang["COMMON"]["VERBS"]["ADD"];?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>