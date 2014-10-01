<?php
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php
 if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination">
        <?php

        echo $presenter->render();

        ?>
        <li>
            <style>
                .pagination-modifier {
                    display: inline-block;
                    margin-left: 5px;
                }

                .pagination-modifier input {
                    max-width: 70px;
                    text-align: center;
                }
            </style>

            {{ Form::open(array('route' => array('admin.pagination', $model['route']), 'class' => "pagination-modifier form-inline")) }}
                {{ Form::text('pagination', null, array('placeholder' => $artificer_pagination, 'class' => 'form-control')) }}
                {{ Form::submit('Show', array('class' => 'btn')); }}
            {{ Form::close() }}
        </li>
    </ul>
<?php endif; ?>