<?php
/**
 * Widget for a new design. Show Psychic Threads (from left menu)
 */
class PsyArThreads extends CWidget
{
    public function run()
    {
        $threads = new PsyArThread("");
        $articles = $threads->_list();
        
        $this->render('psyArThreads', array('art' => $articles));
    }
}
?>
