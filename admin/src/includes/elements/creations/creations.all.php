<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["CREATIONS"]["ALL_MY_CREATIONS"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php $allCreations = getAllCreations(); $id="id";?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!--                <div class="panel-heading">-->
                <!--                    --><?php //echo $iniLang["USERS"]["ALL_USERS"];?>
                <!--                </div>-->
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <?php if ($allCreations):?>
                    <div class="dataTable_wrapper">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="all-users-table">
                            <thead>
                            <tr style="height = 50px;">
                                <th class="cb"></th>
                                <th><?php echo $iniLang["CREATIONS"]["CREATION_NAME"];?></th>
                                <th><?php echo $iniLang["CREATIONS"]["SHORT_DESCRIPTION"];?></th>
                                <th><?php echo $iniLang["CREATIONS"]["CREATION_TYPE"];?></th>
                                <th><?php echo $iniLang["CREATIONS"]["DATE_ADDED"];?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($allCreations as $creation):?>
                                <tr>
                                    <td class="cb"><input type="checkbox" name='users[]' value="<?php echo $creation[$id];?>"/></td>
                                    <td><?php echo $creation["name"];?></td>
                                    <td><?php echo $creation["shortDescription"];?></td>
                                    <td><?php echo $iniLang["CREATIONS"]["TYPES"][$creation["creationType"]];?></td>
                                    <td><?php echo $creation["dateAdded"];?></td>
                                    <td><a href="index.php?p=creations&c=edit&id=<?php echo $creation["id"];?>"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                                <?php $i++;
                            endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php else :
                        displayWarningMessage($iniLang["WARNING_MESSAGES"]["NO_OWN_CREATIONS_USER"]);
                    endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#all-users-table')
                .DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
                    },
                    columnDefs: [
                        {"orderable": true,"orderDataType": "dom-checkbox", targets: 0},
                        {"orderable": true, "searchable": true, targets: 1},
                        {"orderable": true, "searchable": true, targets: 2},
                        {"orderable": false, "searchable": false, targets: 5}
                    ],
                    responsive: true
                });

            /*Launch script for checkboxes*/
            $('#all-users-table tr')
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
            /*Lancement du script dataTable dans la bonne langue. Peut se changer par la suite :)*/
        });
    </script>
</div>