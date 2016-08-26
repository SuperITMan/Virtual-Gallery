<?php


function displayErrorMessage ($message) {
    if (!empty($message)) :?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <?php echo $message;?>
        </div>
    <?php endif;
}

function displaySuccessMessage ($message) {
    if (!empty($message)) :?>
        <div class="alert alert-success" role="alert">
            <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            <span class="sr-only">Success:</span>
            <?php echo $message;?>
        </div>
    <?php endif;
}

function displayWarningMessage ($message) {
    if (!empty($message)) :?>
        <div class="alert alert-warning" role="alert">
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            <span class="sr-only">Warning:</span>
            <?php echo $message;?>
        </div>
    <?php endif;
}

function displaySearchTable ($args, $tableId, $title, $tableType, $id=NULL, $typeArg=NULL) {
    global $iniLang;
    global $iniField;

    if (empty($id))
        $id = "";

    if (strcmp(strtolower($tableType), "checkbox") == 0) $isCheckbox = true;
    else $isCheckbox = false;

    if (strcmp(strtolower($tableType), "click") == 0) $isOnClick = true;
    else $isOnClick = false;

    if (strcmp(strtolower($tableType), "myfiles") == 0) $isMyFiles = true;
    else $isMyFiles = false;

    if (strcmp(strtolower($tableType), "mygroups") == 0) $isMyGroups = true;
    else $isMyGroups = false;

    if (!empty($args)) :?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $title;?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered table-hover"
                        data-order='[[ <?php echo $isCheckbox?1:0;?>, "asc" ]]'
                        id="<?php echo $tableId;?>">
                        <thead>
                        <tr style="height = 50px;">
                            <?php if ($isCheckbox) :?>
                                <th class="cb"></th> <!--DEBUG-->
                            <?php endif;?>
                            <?php $keys = array_keys($args[0]);
                            for ($i = 0; $i <count($keys); $i++) :?>
                                <?php if($keys[$i] != $id) :?>
                                    <th><?php echo $iniLang['datatables'][$keys[$i]];?></th>
                                <?php endif;?>
                            <?php endfor;?>
                            <?php if ($isMyFiles OR $isMyGroups){
                                ?><th></th>
                                <th></th><?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        foreach ($args as $argsValue) {
                            if ($isOnClick) :?>
                                <tr onclick="document.location='<?php echo $typeArg.$argsValue[$id];?>';">
                            <?php else : ?>
                                <tr>
                            <?php endif;?>
                            <?php if ($isCheckbox) :?>
                                <td class="cb"><input type="checkbox" name='<?php echo $typeArg;?>[]' value="<?php echo $argsValue[$id];?>"/></td>
                            <?php endif;?>

                            <?php foreach ($argsValue as $key => $value) {
                                if($key != $id) {
                                    if ($isMyFiles AND $key=="fileType"){?>
                                        <td><i title="<?php echo $iniLang['fileType'][$value];?>" class="<?php echo $iniField['icon'][$value];?> fa-2x"></i></td><?php
                                    }else{?>
                                        <td><?php echo $value;?></td><?php }
                                }
                            }
                            if ($isMyFiles){?>
                                <td><a class = "downloadLink" href="uploads/<?php echo $argsValue[$id];?>" download="<?php echo $argsValue['fileName'];?>"><i class="fa fa-download fa-2x"></i> <?php echo $iniLang['datatables']['download'];?></a></td>
                                <td><a class = "deleteBin" href="#<?php echo $argsValue[$id];?>"><i class="fa fa-trash fa-2x"></i> <?php echo $iniLang['datatables']['remove'];?></a></td><?php
                            }
                            if ($isMyGroups){?>
                                <td><a class = "downloadLink" href="index.php?page=chat&group=<?php echo $argsValue[$id];?>"><i class="<?php echo $iniField['icon']['message'];?>"></i> <?php echo $iniLang['datatables']['sendMessage'];?></a></td>
                                <td><a class = "deleteBin" href="index.php?page=groups&choice=settings&group=<?php echo $argsValue[$id];?>"><i class="<?php echo $iniField['icon']['settings'];?>"></i> <?php echo $iniLang['datatables']['settings'];?></a></td><?php
                            }

                            ?>
                            </tr>
                            <?php
                            $i++;
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                if ('<?php echo $isCheckbox?1:0;?>' == '1') {
                    $('<?php echo "#".$tableId;?>')
                        .dataTable({
                            language: {
                                url: "assets/js/dataTables/dataTables.french.json"
                            },
                            columnDefs: [
                                {"orderDataType": "dom-checkbox", targets: 0}
                            ]
                        });

                    /*Launch script for checkboxes*/
                    $('<?php echo "#".$tableId;?> tr')
                        .filter(':has(:checkbox:checked)')
                        .addClass('selected')
                        .end()
                        .click(function(event) {
                            $(this).toggleClass('selected');
                            if (event.target.type !== 'checkbox') {
                                $(':checkbox', this).attr('checked', function() {
                                    return !this.checked;
                                });
                            }
                        });
                }

                if ('<?php echo $isOnClick?1:0;?>' == '1') {
                    $('<?php echo "#".$tableId;?>')
                        .dataTable({
                            language: {
                                url: "assets/js/dataTables/dataTables.french.json"
                            }
                        });
                }
                else if ('<?php echo $isMyFiles?1:0;?>' == '1') {
                    $('<?php echo "#".$tableId;?>')
                        .dataTable({
                            language: {
                                url: "assets/js/dataTables/dataTables.french.json"
                            },
                            columnDefs: [
                                {"orderable": false, "searchable": false, targets: 5},
                                {"orderable": false, "searchable": false, targets: 4}
                            ]
                        });
                }
                else if ('<?php echo $isMyGroups?1:0;?>' == '1') {
                    $('<?php echo "#".$tableId;?>')
                        .dataTable({
                            language: {
                                url: "assets/js/dataTables/dataTables.french.json"
                            },
                            columnDefs: [
                                {"orderable": false, "searchable": false, targets: 3},
                                {"orderable": false, "searchable": false, targets: 4}
                            ]
                        });
                }

                /*Lancement du script dataTable dans la bonne langue. Peut se changer par la suite :)*/

            });
        </script>

    <?php endif;
}


?>