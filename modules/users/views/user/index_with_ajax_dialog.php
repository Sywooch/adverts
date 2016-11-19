        <?= LinkAjaxDialog::widget([
            'id' => 'user-grid-create',
            'initElement' => '#user-grid-create-button',
            'clientOptions' => [
                'title' =>  UsersModule::t('back', 'User creation'),
                'autoOpen' => false,
                'modal' => true,
                'width' => '95%',
                'height' => 'auto',
                'resizable' => false,
            ],
            'clientEvents' => [
                'close' => "function() {
$.pjax.reload({container: '#user-grid-pjax'})
}"
            ]
        ]) ?>

        <?= LinkAjaxDialog::widget([
            'id' => 'user-grid-view',
            'initElement' => '#user-grid td a[data-view]',
            'clientOptions' => [
                'title' => UsersModule::t('back', 'Editing user: '),
                'autoOpen' => false,
                'modal' => true,
                'width' => '95%',
                'height' => 'auto',
                'resizable' => 'false'
            ],
        ]) ?>

        <?= LinkAjaxDialog::widget([
            'id' => 'user-grid-update',
            'initElement' => '#user-grid td a[data-update]',
            'clientOptions' => [
                'title' => UsersModule::t('back', 'Editing user: '),
                'autoOpen' => false,
                'modal' => true,
                'width' => '95%',
                'height' => 'auto',
                'resizable' => 'false'
            ],
            'clientEvents' => [
                'close' => "function() {
$.pjax.reload({container: '#user-grid-pjax'})
}"
            ]
        ]) ?>

        <?= LinkAjaxDialog::widget([
            'id' => 'user-grid-change-password',
            'initElement' => '#user-grid td a[data-change-password]',
            'clientOptions' => [
                'title' => '',
                'autoOpen' => false,
                'modal' => true,
                'width' => '30%',
                'height' => 'auto',
                'resizable' => 'false'
            ]
        ]) ?>

        <?= LinkAjaxDialog::widget([
            'id' => 'user-grid-set-roles',
            'initElement' => '#user-grid td a[data-set-roles]',
            'clientOptions' => [
                'title' => '',
                'autoOpen' => false,
                'modal' => true,
                'width' => '95%',
                'height' => 'auto',
                'resizable' => 'false'
            ]
        ]) ?>