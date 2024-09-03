import * as jQuery from '../../node_modules/jquery/dist/jquery.min'
import '../../node_modules/bootstrap/dist/js/bootstrap.bundle.js';
import '../../node_modules/popper.js/dist/popper.min';
window.csrfToken = "{{ csrf_token() }}";
import './chat';
import './script.js';