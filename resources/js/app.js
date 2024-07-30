import * as jQuery from '../../node_modules/jquery/dist/jquery.min'
import '../../node_modules/bootstrap/dist/js/bootstrap.bundle.js';
import '../../node_modules/popper.js/dist/popper.min';


function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();