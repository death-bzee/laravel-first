import './bootstrap.js';

import 'sass/styles.sass';
import 'sass/filament/styles.sass';
import 'sass/qr-scanner/styles.sass';

import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';

UIkit.use(Icons);
window.UIkit = UIkit;

import 'sass/select2/styles.sass';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css'
//import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';

import TomSelect from "tom-select";
window.TomSelect = TomSelect
