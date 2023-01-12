import CodeMirror from 'codemirror'
import yaml from 'codemirror/mode/yaml/yaml';
import php from 'codemirror/mode/php/php';
import xml from 'codemirror/mode/xml/xml';
import simple from 'codemirror/addon/mode/simple';
import sublime from 'codemirror/keymap/sublime';
import sc from 'codemirror/addon/search/searchcursor';
// mysql
import sql from 'codemirror/mode/sql/sql';

// autocomplete
import autocomplete from 'codemirror/addon/hint/show-hint';
import autocompleteCss from 'codemirror/addon/hint/show-hint.css';
import autocompleteSql from 'codemirror/addon/hint/sql-hint';
import autocompleteHtml from 'codemirror/addon/hint/html-hint';
import autocompleteJs from 'codemirror/addon/hint/javascript-hint';

// autorefresh
import autoRefresh from 'codemirror/addon/display/autorefresh';

window.CodeMirror = CodeMirror;