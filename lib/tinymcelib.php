<?php

global $CFG;

/**
 * An iterator implementation that will return an array of URIs to needed TinyMCE JavaScripts.  We need
 * to do this over a straight array since we'll need access to $COURSE, which we don't have a valid copy
 * of yet in setup.php.  Once this is evaulated in javascript.php we should have $COURSE and can return 
 * proper URIs.
 */
class TinyMCEScriptIterator implements ArrayAccess, Iterator   {
    private $position = 0;
    
    // This array must be an empty array of the same length and value position as the array we'll return
    // via current() and offsetGet().  Need this for simple array operations and checks.
    private $placeholder = array(0 => '');

    public function __construct() {
        $this->position = 0;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->offsetGet($this->position);
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return $this->offsetExists($this->position);
    }
    
    public function offsetSet($offset, $value) {
        // This is not editable.
        return;
    }

    public function offsetUnset($offset) {
        // This is not editable.
        return;
    }

    public function offsetExists($offset) {
        return isset($this->placeholder[$offset]);
    }

    public function offsetGet($offset) {
        return $this->getscript($offset);
    }
    
    private function getscript($index) {
        global $CFG, $COURSE, $HTTPSPAGEREQUIRED;

        $result = null;

        switch($index) {
            case 0:
                $courseid = $COURSE->id;
                if (!empty($courseid) and has_capability('moodle/course:managefiles', get_context_instance(CONTEXT_COURSE, $courseid))) {
                    $httpsrequired = empty($HTTPSPAGEREQUIRED) ? '' : '&amp;httpsrequired=1';
                    $result = "{$CFG->httpswwwroot}/lib/editor/tinymce/tinymcejs.php?id={$courseid}{$httpsrequired}";
                } else {
                    $httpsrequired = empty($HTTPSPAGEREQUIRED) ? '' : '?httpsrequired=1';
                    $result = "{$CFG->httpswwwroot}/lib/editor/tinymce/tinymcejs.php{$httpsrequired}";
                }
                break;
        }

        return $result;
    }
}

// Populating editorsrc with an array equivalent will disable inclusion of HTMLArea's inline <script>
// tags and make javascript.php include our TinyMCE JavaScript.
$CFG->editorsrc = new TinyMCEScriptIterator();