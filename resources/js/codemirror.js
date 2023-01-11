import CodeMirror from 'codemirror'
import yaml from 'codemirror/mode/yaml/yaml';
import php from 'codemirror/mode/php/php';
import xml from 'codemirror/mode/xml/xml';
import simple from 'codemirror/addon/mode/simple';
import sublime from 'codemirror/keymap/sublime';
import sc from 'codemirror/addon/search/searchcursor';

// autorefresh
import autoRefresh from 'codemirror/addon/display/autorefresh';

window.CodeMirror = CodeMirror;