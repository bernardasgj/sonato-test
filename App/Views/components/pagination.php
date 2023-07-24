<?php
    // Preserve the current search term in the pagination links
    $paginationLinks = '';
    if ($totalPages > 1) {
        $numLinksToShow = 2;
        $startPage = max(1, $currentPage - $numLinksToShow);
        $endPage = min($totalPages, $currentPage + $numLinksToShow);

        if ($currentPage != 1) {
            $paginationLinks .= '<a class="previous-page" href="' . $currentQuery . '&page=' . ($currentPage - 1) . '"><i class="fa fa-angle-left"></i></a>';
        }

        if ($currentPage > 3) {
            $paginationLinks .= '<a href="' . $currentQuery . '&page=1">1</a>';
        }

        if ($startPage > 2) {
            $paginationLinks .= '<span class="dots">...</span>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i === $currentPage) {
                $paginationLinks .= '<span class="current">' . $i . '</span>';
            } else {
                $paginationLinks .= '<a href="' . $currentQuery . '&page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($endPage + 1 < $totalPages) {
            $paginationLinks .= '<span class="dots">...</span>';
        }

        if ($endPage < $totalPages) {
            $paginationLinks .= '<a href="' . $currentQuery . '&page=' . $totalPages . '">' . $totalPages . '</a>';
        }

        if ($currentPage != $totalPages) {
            $paginationLinks .= '<a class="next-page" href="' . $currentQuery . '&page=' . ($currentPage + 1) . '"><i class="fa fa-angle-right"></i></a>';
        }

        echo '<div class="pagination" data-pagination-container id="paginationContainer">' . $paginationLinks . '</div>';
    }
?>
