<?php
if(!defined('DOKU_INC')) die();
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_marginnotes extends DokuWiki_Syntax_Plugin {
    public function getPluginName() {
        return $this->getInfo()['base'];
    }
    
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<mnote>(?=.*</mnote>)', $mode, 'plugin_' . $this->getPluginName());
    }
    
    public function postConnect() {
        $this->Lexer->addExitPattern('</mnote>', 'plugin_' . $this->getPluginName());
    }

    function getSort() {
        return 69;
    }

    function getType() {
        return 'protected';
    }
    
    function getPType(){
        return 'block';
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_UNMATCHED:
                return [
                    'state' => $state,
                    'match' => $match,
                    'pos' => $pos - strlen('<mnote>'),
                ];
        }
        
        return [
            'state' => $state
        ];
    }
    
    function render($mode, Doku_Renderer $renderer, $data) {
        switch ($data['state']) {
            case DOKU_LEXER_ENTER:
                $renderer->doc .= '<div class="marginnote">';
                break;
            case DOKU_LEXER_UNMATCHED:
                $renderer->doc .= '<span>' . $data['match'] . '</span>';
                break;
            case DOKU_LEXER_EXIT:
                $renderer->doc .= '</div>';
                break;
        }
        
        return true;
    }
}
