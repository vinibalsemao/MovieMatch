<?php
function renderPagination($currentPage, $totalPages)
{
    $prevPage = $currentPage > 1 ? $currentPage - 1 : null;
    $nextPage = $currentPage < $totalPages ? $currentPage + 1 : null;

    echo '<nav>';
    echo '<ul class="pagination">';
    if ($prevPage) {
        echo '<li><a href="?page=' . $prevPage . '">&laquo; </a></li>';
    }

    for ($i = 1; $i <= 22; $i++) {
        echo '<li' . ($i == $currentPage ? ' class="active"' : '') . '>';
        echo '<a href="?page=' . $i . '">' . $i . '</a>';
        echo '</li>';
    }

    if ($nextPage) {
        echo '<li><a href="?page=' . $nextPage . '"> &raquo;</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
}
