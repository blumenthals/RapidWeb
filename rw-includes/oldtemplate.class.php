<?php

class OldTemplate extends View {
    public function render($templateFile) {
        global $VARIABLES;
        extract($VARIABLES);
        include $templateFile;
    }

    public function do_head() {
        $this->loadJavascript('jquery-1.7.js');
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
