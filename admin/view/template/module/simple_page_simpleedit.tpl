<vtab title="<?php echo $l->get('tab_simpleedit', true) ?>" title-lang-id="tab_simpleedit" ng-init="settings.edit = !empty(settings.edit) ? settings.edit : {}">
    <table class="form">
        <tr>
            <td><?php echo $l->get('entry_replace_edit') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.replaceEdit">
            </td>
        </tr>
        <tr>
            <td><?php echo $l->get('entry_scroll_to_error') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.edit.scrollToError">
            </td>
        </tr>
    </table>
    <table class="form" ng-controller="simpleSetController">
        <tr>
            <td ng-init="settings.edit.rows = !empty(settings.edit.rows) ? settings.edit.rows : {};setData.rows=settings.edit.rows;setData.filterForObjects=['customer'];sortAllRows();">
                <rows></rows>
            </td>
        </tr>
    </table>
</vtab>