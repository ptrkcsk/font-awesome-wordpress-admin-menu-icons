<?php

namespace Fawpami;

class Scripts
{
    /** @var string[] */
    private $menuPages = [];

    /** @var string[] */
    private $postTypes = [];

    private function iconStyle(string $class): string
    {
        return <<<JS
document
    .querySelectorAll('.{$class} .svg')[0]
    .classList
    .add('wp-menu-image--fawpami');
JS;
    }

    public function registerMenuPage(string $page): void
    {
        $this->menuPages[] = $page;
    }

    public function registerPostType(string $postType): void
    {
        $this->postTypes[] = $postType;
    }

    public function printScripts(): void
    {
        if ($this->menuPages || $this->postTypes) {
            $menuPageStyles = implode("\n", array_map(function ($page) {
                return $this->iconStyle("toplevel_page_{$page}");
            }, $this->menuPages));
            $postTypeStyles = implode("\n", array_map(function ($post) {
                return $this->iconStyle("menu-icon-{$post}");
            }, $this->postTypes));

            echo <<<HTML
<!-- FA WP Admin Menu Icons icon styles -->
<script>
(function (){
    function main() {
        {$menuPageStyles}
        {$postTypeStyles}
    }

    if (document.readyState !== 'loading') {
        main();
    } else {
        document.addEventListener('DOMContentLoaded', main);
    }
})();
</script>
HTML;
        }
    }
}
