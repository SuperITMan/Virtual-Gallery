<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["NEWS"]["ALL_NEWS"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php $allNews = getAllNews(); $id="id";?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <?php if ($allNews):?>
                        <div class="dataTable_wrapper">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="all-news-table">
                                <thead>
                                <tr style="height = 50px;">
                                    <th class="cb"></th>
                                    <th><?php echo $iniLang["COMMON"]["WORDS"]["TITLE"];?></th>
                                    <th><?php echo $iniLang["COMMON"]["WORDS"]["CONTENT"];?></th>
                                    <th><?php echo $iniLang["NEWS"]["CREATION_DATE"];?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                foreach ($allNews as $news):?>
                                    <tr>
                                        <td class="cb"><input type="checkbox" name='news[]' value="<?php echo $news["id"];?>"/></td>
                                        <td><?php echo $news["title"];?></td>
                                        <td><?php echo $news["content"];?></td>
                                        <td><?php echo $news["creationDate"];?></td>
                                        <td><a href="index.php?p=news&c=edit&id=<?php echo $news["id"];?>"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
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
            $('#all-news-table')
                .DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
                    },
                    columnDefs: [
                        {"orderable": true,"orderDataType": "dom-checkbox", targets: 0},
                        {"orderable": true, "searchable": true, targets: 1},
                        {"orderable": false, "searchable": true, targets: 2},
                        {"orderable": true, "searchable": true, targets: 3},
                        {"orderable": false, "searchable": false, targets: 4}
                    ],
                    responsive: true
                });

            /*Launch script for checkboxes*/
            $('#all-news-table tr')
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