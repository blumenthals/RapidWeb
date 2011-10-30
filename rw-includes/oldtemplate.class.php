<?php

class OldTemplate extends View {
    public function render($templateFile) {
        global $VARIABLES;
        include $templateFile;
    }

    protected function do_head() {
        if($plugin = $this->getPlugin()) $plugin->do_head($this->page);
    }

    protected function the_content() {
        if($plugin = $this->getPlugin()) {
            $plugin->the_content($this->page);
        } else {
            // @todo use WikiPage class/plugin
            echo "###CONTENT###";
        }
    }

    protected function getPlugin() {
        return $this->rapidweb->getPageType($this->page->page_type);
    }

}
