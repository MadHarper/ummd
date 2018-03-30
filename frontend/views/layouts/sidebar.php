<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">

        <?php
            $menu=[];
            if(isset($this->context->menu))
                $menu = array_merge([['label' => \Yii::$app->name, 'options' => ['class' => 'nav-header']]], $this->context->menu);

        ?>
        <?= jcabanillas\inspinia\widgets\Menu::widget(
            [
                'options' => ['class' => 'nav metismenu', 'id'=>'side-menu'],
                'submenuTemplate' => "\n<ul class='nav nav-second-level collapse' {show}>\n{items}\n</ul>\n",
                'items' => [
                    ['label' => 'Справочники', 'icon' => 'fa fa-file-code-o', 'url' => '#', 'visible' => Yii::$app->user->can('viewDirectory'),
                        'items' => [
                        ['label' => 'Страны', 'icon' => 'fa fa-globe', 'url' => ['/catalog/country/index'], 'visible' => true],
                        ['label' => 'Города', 'icon' => 'fa fa-globe', 'url' => ['/catalog/city/index'], 'visible' => true],
                        ['label' => 'Субъекты РФ', 'icon' => 'fa fa-globe', 'url' => ['/catalog/region/index'], 'visible' => true],
                        ['label' => 'Организации', 'icon' => 'fa fa-university', 'url' => ['/catalog/organization/index'], 'visible' => true],
                        ['label' => 'Должностные лица', 'icon' => 'fa fa-address-book-o', 'url' => ['/catalog/employee/index'], 'visible' => true],
                        ['label' => 'Типы документов', 'icon' => 'fa fa-address-book-o', 'url' => ['/catalog/document-type/index'], 'visible' => true],
                    ]],
                    ['label' => 'Соглашения', 'icon' => 'fa fa-handshake-o', 'url' => ['/agreement/default/index'], 'visible' => true],
                    ['label' => 'Документы', 'icon' => 'fa fa-file-word-o', 'url' => ['/agreement/document/list'], 'visible' => true],
                    ['label' => 'Командировки', 'icon' => 'fa fa-paper-plane', 'url' => ['/mission/default/index'], 'visible' => true],

                ],

            ]
        ) ?>
    </div>
</nav>