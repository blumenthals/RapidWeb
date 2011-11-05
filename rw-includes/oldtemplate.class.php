<?php

class OldTemplate extends View {
    public function render($templateFile) {
        global $VARIABLES;
        include $templateFile;
    }

    public function do_head() {
        echo "<script src='{$this->rapidweb->globalURL}/jquery-1.6.4.min.js'></script>";
        if($plugin = $this->getPlugin()) $plugin->do_head($this->page);
    }

    public function the_content() {
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

    public function the_title() {
        echo "###PAGE###";
    }

}
